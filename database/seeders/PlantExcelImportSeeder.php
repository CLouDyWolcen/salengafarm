<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PlantExcelImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/imports/202230000AC_EFG_EFGLS-MAIN-PRICELIST_CHE_25102023-1.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->error("Excel file not found at: {$filePath}");
            $this->command->info("Please place the Excel file in: storage/app/imports/");
            return;
        }

        $this->command->info("Loading Excel file...");
        $spreadsheet = IOFactory::load($filePath);
        
        // Import from different sheets
        $this->importShrubs($spreadsheet);
        $this->importTrees($spreadsheet);
        $this->importPalms($spreadsheet);
        $this->importGrass($spreadsheet);
        $this->importBamboo($spreadsheet);
        $this->importHerbs($spreadsheet);
        $this->importFertilizer($spreadsheet);
        
        $this->command->info("Plant import completed successfully!");
    }

    private function importShrubs($spreadsheet): void
    {
        $this->command->info("Processing SHRUB sheet...");
        
        $sheet = $spreadsheet->getSheetByName('SHRUB');
        if (!$sheet) {
            $this->command->warn("SHRUB sheet not found, skipping...");
            return;
        }

        // Data starts at row 4 (after headers)
        $rowNumber = 4;
        $imported = 0;
        $updated = 0;

        while (true) {
            $commonName = trim($sheet->getCell("B{$rowNumber}")->getValue() ?? '');
            
            // Stop if we hit empty rows or special markers
            if (empty($commonName) || is_numeric($commonName) || $commonName === 'VEGGIES') {
                break;
            }

            $code = trim($sheet->getCell("C{$rowNumber}")->getValue() ?? '');
            $scientificName = trim($sheet->getCell("D{$rowNumber}")->getValue() ?? '');
            $heightMm = $this->parseNumeric($sheet->getCell("E{$rowNumber}")->getValue());
            $spreadMm = $this->parseNumeric($sheet->getCell("F{$rowNumber}")->getValue());
            $spacingMm = $this->parseNumeric($sheet->getCell("G{$rowNumber}")->getValue());
            $costPerSqm = $this->parseNumeric($sheet->getCell("H{$rowNumber}")->getValue());
            $pcsPerSqm = $this->parseNumeric($sheet->getCell("I{$rowNumber}")->getValue());
            $unitCost = $this->parseNumeric($sheet->getCell("J{$rowNumber}")->getValue());
            $costPerMm = $this->parseNumeric($sheet->getCell("K{$rowNumber}")->getValue());

            // Skip rows with no meaningful data
            if (empty($code) && $heightMm === 0 && $unitCost === 0) {
                $rowNumber++;
                continue;
            }

            $data = [
                'name' => $commonName,
                'code' => !empty($code) ? $code : null,
                'scientific_name' => !empty($scientificName) && $scientificName !== '-' ? $scientificName : null,
                'category' => 'shrub',
                'height_mm' => $heightMm,
                'spread_mm' => $spreadMm,
                'spacing_mm' => $spacingMm,
                'price' => $unitCost,
                'cost_per_sqm' => $costPerSqm,
                'pieces_per_sqm' => $pcsPerSqm,
                'cost_per_mm' => $costPerMm,
                'quantity' => 0,
                'updated_at' => now(),
            ];

            // Check if plant exists
            $existing = DB::table('plants')
                ->where('category', 'shrub')
                ->where('name', $commonName)
                ->first();

            if ($existing) {
                DB::table('plants')->where('id', $existing->id)->update($data);
                $updated++;
                $this->command->info("  Updated: {$commonName}");
            } else {
                $data['created_at'] = now();
                DB::table('plants')->insert($data);
                $imported++;
                $this->command->info("  Added: {$commonName}");
            }

            $rowNumber++;
        }

        $this->command->info("Shrubs: {$imported} added, {$updated} updated");
    }

    private function importTrees($spreadsheet): void
    {
        $this->command->info("Processing TREE sheet...");
        
        $sheet = $spreadsheet->getSheetByName('TREE');
        if (!$sheet) {
            $this->command->warn("TREE sheet not found, skipping...");
            return;
        }

        $rowNumber = 4;
        $imported = 0;
        $updated = 0;

        while (true) {
            $commonName = trim($sheet->getCell("B{$rowNumber}")->getValue() ?? '');
            
            if (empty($commonName) || is_numeric($commonName)) {
                break;
            }

            $code = trim($sheet->getCell("C{$rowNumber}")->getValue() ?? '');
            $scientificName = trim($sheet->getCell("D{$rowNumber}")->getValue() ?? '');
            $heightMm = $this->parseNumeric($sheet->getCell("E{$rowNumber}")->getValue());
            $spreadMm = $this->parseNumeric($sheet->getCell("F{$rowNumber}")->getValue());
            $caliperMm = $sheet->getCell("G{$rowNumber}")->getValue(); // Keep as string (e.g., "80-100")
            $costPerTree = $this->parseNumeric($sheet->getCell("H{$rowNumber}")->getValue());
            $pcsPerSqm = $this->parseNumeric($sheet->getCell("I{$rowNumber}")->getValue());
            $unitCost = $this->parseNumeric($sheet->getCell("J{$rowNumber}")->getValue());
            $costPerMm = $this->parseNumeric($sheet->getCell("K{$rowNumber}")->getValue());

            if (empty($code) && $heightMm === 0 && $unitCost === 0) {
                $rowNumber++;
                continue;
            }

            $data = [
                'name' => $commonName,
                'code' => !empty($code) && $code !== '-' ? $code : null,
                'scientific_name' => !empty($scientificName) && $scientificName !== '-' ? $scientificName : null,
                'category' => 'tree',
                'height_mm' => $heightMm,
                'spread_mm' => $spreadMm,
                'oc' => $caliperMm ? (string)$caliperMm : null, // Store caliper in OC field
                'price' => $unitCost > 0 ? $unitCost : $costPerTree,
                'cost_per_sqm' => $costPerTree,
                'pieces_per_sqm' => $pcsPerSqm,
                'cost_per_mm' => $costPerMm,
                'quantity' => 0,
                'updated_at' => now(),
            ];

            $existing = DB::table('plants')
                ->where('category', 'tree')
                ->where('name', $commonName)
                ->first();

            if ($existing) {
                DB::table('plants')->where('id', $existing->id)->update($data);
                $updated++;
                $this->command->info("  Updated: {$commonName}");
            } else {
                $data['created_at'] = now();
                DB::table('plants')->insert($data);
                $imported++;
                $this->command->info("  Added: {$commonName}");
            }

            $rowNumber++;
        }

        $this->command->info("Trees: {$imported} added, {$updated} updated");
    }

    private function importPalms($spreadsheet): void
    {
        $this->command->info("Processing PALM sheet...");
        
        $sheet = $spreadsheet->getSheetByName('PALM');
        if (!$sheet) {
            $this->command->warn("PALM sheet not found, skipping...");
            return;
        }

        $rowNumber = 4;
        $imported = 0;
        $updated = 0;

        while (true) {
            $commonName = trim($sheet->getCell("B{$rowNumber}")->getValue() ?? '');
            
            if (empty($commonName) || is_numeric($commonName)) {
                break;
            }

            $code = trim($sheet->getCell("C{$rowNumber}")->getValue() ?? '');
            $scientificName = trim($sheet->getCell("D{$rowNumber}")->getValue() ?? '');
            $heightMm = $this->parseNumeric($sheet->getCell("E{$rowNumber}")->getValue());
            $spreadMm = $this->parseNumeric($sheet->getCell("F{$rowNumber}")->getValue());
            $caliperMm = $sheet->getCell("G{$rowNumber}")->getValue();
            $costPerPalm = $this->parseNumeric($sheet->getCell("H{$rowNumber}")->getValue());
            $pcsPerSqm = $this->parseNumeric($sheet->getCell("I{$rowNumber}")->getValue());
            $unitCost = $this->parseNumeric($sheet->getCell("J{$rowNumber}")->getValue());
            $costPerMm = $this->parseNumeric($sheet->getCell("K{$rowNumber}")->getValue());

            if (empty($code) && $heightMm === 0 && $unitCost === 0) {
                $rowNumber++;
                continue;
            }

            $data = [
                'name' => $commonName,
                'code' => !empty($code) ? $code : null,
                'scientific_name' => !empty($scientificName) ? $scientificName : null,
                'category' => 'palm',
                'height_mm' => $heightMm,
                'spread_mm' => $spreadMm,
                'oc' => $caliperMm ? (string)$caliperMm : null,
                'price' => $unitCost > 0 ? $unitCost : $costPerPalm,
                'cost_per_sqm' => $costPerPalm,
                'pieces_per_sqm' => $pcsPerSqm,
                'cost_per_mm' => $costPerMm,
                'quantity' => 0,
                'updated_at' => now(),
            ];

            $existing = DB::table('plants')
                ->where('category', 'palm')
                ->where('name', $commonName)
                ->first();

            if ($existing) {
                DB::table('plants')->where('id', $existing->id)->update($data);
                $updated++;
                $this->command->info("  Updated: {$commonName}");
            } else {
                $data['created_at'] = now();
                DB::table('plants')->insert($data);
                $imported++;
                $this->command->info("  Added: {$commonName}");
            }

            $rowNumber++;
        }

        $this->command->info("Palms: {$imported} added, {$updated} updated");
    }

    private function importGrass($spreadsheet): void
    {
        $this->command->info("Processing GRASS sheet...");
        
        $sheet = $spreadsheet->getSheetByName('GRASS');
        if (!$sheet) {
            $this->command->warn("GRASS sheet not found, skipping...");
            return;
        }

        $rowNumber = 4;
        $imported = 0;
        $updated = 0;

        while (true) {
            $commonName = trim($sheet->getCell("B{$rowNumber}")->getValue() ?? '');
            
            if (empty($commonName) || is_numeric($commonName)) {
                break;
            }

            $code = trim($sheet->getCell("C{$rowNumber}")->getValue() ?? '');
            $scientificName = trim($sheet->getCell("D{$rowNumber}")->getValue() ?? '');
            $heightMm = $this->parseNumeric($sheet->getCell("F{$rowNumber}")->getValue());
            $spreadMm = $this->parseNumeric($sheet->getCell("G{$rowNumber}")->getValue());
            $spacingMm = $this->parseNumeric($sheet->getCell("H{$rowNumber}")->getValue());
            $pcsPerSqm = $this->parseNumeric($sheet->getCell("I{$rowNumber}")->getValue());
            $unitCost = $this->parseNumeric($sheet->getCell("J{$rowNumber}")->getValue());
            $costPerSqm = $this->parseNumeric($sheet->getCell("K{$rowNumber}")->getValue());

            if (empty($code) && $heightMm === 0 && $unitCost === 0 && $pcsPerSqm === 0) {
                $rowNumber++;
                continue;
            }

            $data = [
                'name' => $commonName,
                'code' => !empty($code) && $code !== '-' ? $code : null,
                'scientific_name' => !empty($scientificName) && $scientificName !== '-' ? $scientificName : null,
                'category' => 'grass',
                'height_mm' => $heightMm,
                'spread_mm' => $spreadMm,
                'spacing_mm' => $spacingMm,
                'price' => $unitCost,
                'cost_per_sqm' => $costPerSqm,
                'pieces_per_sqm' => $pcsPerSqm,
                'quantity' => 0,
                'updated_at' => now(),
            ];

            $existing = DB::table('plants')
                ->where('category', 'grass')
                ->where('name', $commonName)
                ->first();

            if ($existing) {
                DB::table('plants')->where('id', $existing->id)->update($data);
                $updated++;
                $this->command->info("  Updated: {$commonName}");
            } else {
                $data['created_at'] = now();
                DB::table('plants')->insert($data);
                $imported++;
                $this->command->info("  Added: {$commonName}");
            }

            $rowNumber++;
        }

        $this->command->info("Grass: {$imported} added, {$updated} updated");
    }

    private function importBamboo($spreadsheet): void
    {
        $this->command->info("Processing BAMBOO sheet...");
        
        $sheet = $spreadsheet->getSheetByName('BAMBOO');
        if (!$sheet) {
            $this->command->warn("BAMBOO sheet not found, skipping...");
            return;
        }

        $rowNumber = 4;
        $imported = 0;
        $updated = 0;

        while (true) {
            $commonName = trim($sheet->getCell("B{$rowNumber}")->getValue() ?? '');
            
            if (empty($commonName) || is_numeric($commonName)) {
                break;
            }

            $code = trim($sheet->getCell("C{$rowNumber}")->getValue() ?? '');
            $scientificName = trim($sheet->getCell("D{$rowNumber}")->getValue() ?? '');
            $heightMm = $this->parseNumeric($sheet->getCell("E{$rowNumber}")->getValue());
            $spreadMm = $this->parseNumeric($sheet->getCell("F{$rowNumber}")->getValue());
            $spacingMm = $this->parseNumeric($sheet->getCell("G{$rowNumber}")->getValue());
            $costPerTree = $this->parseNumeric($sheet->getCell("H{$rowNumber}")->getValue());
            $pcsPerSqm = $this->parseNumeric($sheet->getCell("I{$rowNumber}")->getValue());
            $unitCost = $this->parseNumeric($sheet->getCell("J{$rowNumber}")->getValue());
            $costPerMm = $this->parseNumeric($sheet->getCell("K{$rowNumber}")->getValue());

            if (empty($scientificName) && $heightMm === 0 && $unitCost === 0) {
                $rowNumber++;
                continue;
            }

            $data = [
                'name' => $commonName,
                'code' => !empty($code) ? $code : null,
                'scientific_name' => !empty($scientificName) ? $scientificName : null,
                'category' => 'bamboo',
                'height_mm' => $heightMm,
                'spread_mm' => $spreadMm,
                'spacing_mm' => $spacingMm,
                'price' => $unitCost > 0 ? $unitCost : $costPerTree,
                'cost_per_sqm' => $costPerTree,
                'pieces_per_sqm' => $pcsPerSqm,
                'cost_per_mm' => $costPerMm,
                'quantity' => 0,
                'updated_at' => now(),
            ];

            $existing = DB::table('plants')
                ->where('category', 'bamboo')
                ->where('name', $commonName)
                ->first();

            if ($existing) {
                DB::table('plants')->where('id', $existing->id)->update($data);
                $updated++;
                $this->command->info("  Updated: {$commonName}");
            } else {
                $data['created_at'] = now();
                DB::table('plants')->insert($data);
                $imported++;
                $this->command->info("  Added: {$commonName}");
            }

            $rowNumber++;
        }

        $this->command->info("Bamboo: {$imported} added, {$updated} updated");
    }

    private function importHerbs($spreadsheet): void
    {
        $this->command->info("Processing HERBS sheet...");
        
        $sheet = $spreadsheet->getSheetByName('HERBS');
        if (!$sheet) {
            $this->command->warn("HERBS sheet not found, skipping...");
            return;
        }

        // Only CITRONELLA has actual data in row 72
        $rowNumber = 72;
        $imported = 0;
        $updated = 0;

        $commonName = trim($sheet->getCell("B{$rowNumber}")->getValue() ?? '');
        
        if (!empty($commonName)) {
            $code = trim($sheet->getCell("C{$rowNumber}")->getValue() ?? '');
            $scientificName = trim($sheet->getCell("D{$rowNumber}")->getValue() ?? '');
            $heightMm = $this->parseNumeric($sheet->getCell("E{$rowNumber}")->getValue());
            $unitCost = $this->parseNumeric($sheet->getCell("J{$rowNumber}")->getValue());

            $data = [
                'name' => $commonName,
                'code' => !empty($code) ? $code : null,
                'scientific_name' => !empty($scientificName) ? $scientificName : null,
                'category' => 'herbs',
                'height_mm' => $heightMm,
                'price' => $unitCost,
                'quantity' => 0,
                'updated_at' => now(),
            ];

            $existing = DB::table('plants')
                ->where('category', 'herbs')
                ->where('name', $commonName)
                ->first();

            if ($existing) {
                DB::table('plants')->where('id', $existing->id)->update($data);
                $updated++;
                $this->command->info("  Updated: {$commonName}");
            } else {
                $data['created_at'] = now();
                DB::table('plants')->insert($data);
                $imported++;
                $this->command->info("  Added: {$commonName}");
            }
        }

        $this->command->info("Herbs: {$imported} added, {$updated} updated");
    }

    private function importFertilizer($spreadsheet): void
    {
        $this->command->info("Processing FERTILIZER sheet...");
        
        $sheet = $spreadsheet->getSheetByName('FERTILIZER');
        if (!$sheet) {
            $this->command->warn("FERTILIZER sheet not found, skipping...");
            return;
        }

        $rowNumber = 4;
        $imported = 0;
        $updated = 0;

        while (true) {
            $commonName = trim($sheet->getCell("B{$rowNumber}")->getValue() ?? '');
            
            if (empty($commonName) || is_numeric($commonName)) {
                break;
            }

            $code = trim($sheet->getCell("C{$rowNumber}")->getValue() ?? '');
            $costPerSqm = $this->parseNumeric($sheet->getCell("D{$rowNumber}")->getValue());
            $pcsPerSqm = $this->parseNumeric($sheet->getCell("E{$rowNumber}")->getValue());
            $unitCost = $this->parseNumeric($sheet->getCell("F{$rowNumber}")->getValue());

            if ($costPerSqm === 0 && $unitCost === 0) {
                $rowNumber++;
                continue;
            }

            $data = [
                'name' => $commonName,
                'code' => !empty($code) && $code !== '-' ? $code : null,
                'category' => 'fertilizer',
                'price' => $unitCost,
                'cost_per_sqm' => $costPerSqm,
                'pieces_per_sqm' => $pcsPerSqm,
                'quantity' => 0,
                'updated_at' => now(),
            ];

            $existing = DB::table('plants')
                ->where('category', 'fertilizer')
                ->where('name', $commonName)
                ->first();

            if ($existing) {
                DB::table('plants')->where('id', $existing->id)->update($data);
                $updated++;
                $this->command->info("  Updated: {$commonName}");
            } else {
                $data['created_at'] = now();
                DB::table('plants')->insert($data);
                $imported++;
                $this->command->info("  Added: {$commonName}");
            }

            $rowNumber++;
        }

        $this->command->info("Fertilizer: {$imported} added, {$updated} updated");
    }

    private function parseNumeric($value)
    {
        if (is_null($value) || $value === '' || $value === '-') {
            return 0;
        }
        
        // Remove commas and convert to float
        $cleaned = str_replace(',', '', $value);
        return is_numeric($cleaned) ? (float)$cleaned : 0;
    }
}
