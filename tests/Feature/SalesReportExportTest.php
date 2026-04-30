<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class SalesReportExportTest extends TestCase
{
    use RefreshDatabase;

    private function fakePngBinary(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO5WnLwAAAAASUVORK5CYII='
        );
    }

    public function test_admin_can_export_excel_and_pdf(): void
    {
        config()->set('reports.quickchart_enabled', true);
        Http::fake([
            '*quickchart.io/*' => Http::response($this->fakePngBinary(), 200, ['Content-Type' => 'image/png']),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        SaleTransaction::factory()->create([
            'user_id' => $admin->id,
            'status' => 'success',
            'created_at' => Carbon::parse('2026-04-13 10:00:00'),
        ]);

        $xlsx = $this->get(route('reports.sales.export', [
            'format' => 'xlsx',
            'period' => 'daily',
            'date' => '2026-04-13',
        ]));
        $xlsx->assertOk();
        $this->assertStringContainsString('.xlsx', (string) $xlsx->headers->get('content-disposition'));

        $pdf = $this->get(route('reports.sales.export', [
            'format' => 'pdf',
            'period' => 'daily',
            'date' => '2026-04-13',
        ]));
        $pdf->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
        $this->assertStringContainsString('.pdf', (string) $pdf->headers->get('content-disposition'));
    }

    public function test_marketing_excel_export_is_scoped_to_owned_users(): void
    {
        config()->set('reports.quickchart_enabled', true);
        Http::fake([
            '*quickchart.io/*' => Http::response($this->fakePngBinary(), 200, ['Content-Type' => 'image/png']),
        ]);

        $marketingA = User::factory()->create(['role' => 'marketing']);
        $marketingB = User::factory()->create(['role' => 'marketing']);

        $userA = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingA->id]);
        $userB = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketingB->id]);

        $inScope = SaleTransaction::factory()->create([
            'user_id' => $userA->id,
            'status' => 'success',
            'customer_name' => 'IN_SCOPE',
            'created_at' => Carbon::parse('2026-04-13 11:00:00'),
        ]);

        $outScope = SaleTransaction::factory()->create([
            'user_id' => $userB->id,
            'status' => 'success',
            'customer_name' => 'OUT_SCOPE',
            'created_at' => Carbon::parse('2026-04-13 12:00:00'),
        ]);

        $this->actingAs($marketingA);

        $response = $this->get(route('marketing.reports.sales.export', [
            'format' => 'xlsx',
            'period' => 'daily',
            'date' => '2026-04-13',
        ]));

        $response->assertOk();
        $binary = $response->baseResponse;
        $path = method_exists($binary, 'getFile') ? $binary->getFile()->getPathname() : null;
        $this->assertNotEmpty($path);

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getSheetByName('Transactions');

        $this->assertNotNull($sheet);

        $rows = $sheet->toArray();
        $ids = collect($rows)
            ->skip(1)
            ->pluck(0)
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();

        $this->assertContains($inScope->id, $ids);
        $this->assertNotContains($outScope->id, $ids);
    }
}
