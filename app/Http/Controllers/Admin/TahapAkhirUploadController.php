<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahapAkhirUpload;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

class TahapAkhirUploadController extends Controller
{
    protected $tahapAkhirUpload;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.tahap-akhir-upload.index')->only('index');
        $this->middleware('can:admin.tahap-akhir-upload.create')->only('create', 'store');
        $this->middleware('can:admin.tahap-akhir-upload.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.tahap-akhir-upload.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = TahapAkhirUpload::query();

        if ($request->filled('search')) {
            $query->where('original_name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate($request->input('per_page', 15))->appends($request->all());

        return view('admin.tahap-akhir-upload.index', compact('items'));
    }

    public function create()
    {
        return view('admin.tahap-akhir-upload.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $file = $validated['excel_file'];
        $path = $file->store('tahap-akhir-upload', 'public');

        TahapAkhirUpload::create([
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.tahap-akhir-upload.index')
            ->with('success', 'File berhasil diunggah.');
    }

    public function show(TahapAkhirUpload $tahapAkhirUpload)
    {
        $filePath = Storage::disk('public')->path($tahapAkhirUpload->file_path);

        abort_unless(file_exists($filePath), 404, 'File tidak ditemukan.');

        $sheets = $this->extractSheetsPreview($filePath);

        return view('admin.tahap-akhir-upload.show', [
            'upload' => $tahapAkhirUpload,
            'sheets' => $sheets,
        ]);
    }

    private function extractSheetsPreview(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheets = [];

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $rows = [];
            $headers = null;
            $rowCount = 0;

            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $this->normalizeCellValue($cell->getValue());
                }

                if ($rowIndex === 1) {
                    $headers = $this->normalizeHeaders($cells);
                    continue;
                }

                if ($headers === null) {
                    $headers = $this->fallbackHeaders(count($cells));
                }

                $rowAssoc = [];
                foreach ($headers as $colIndex => $header) {
                    $rowAssoc[$header] = $cells[$colIndex] ?? null;
                }

                $rows[] = $rowAssoc;
                $rowCount++;

                if ($rowCount >= 200) {
                    break;
                }
            }

            if (!empty($rows)) {
                if ($headers === null) {
                    $headers = $this->fallbackHeaders(count($rows[0]));
                }

                $sheetName = method_exists($sheet, 'getTitle')
                    ? $sheet->getTitle()
                    : (method_exists($sheet, 'getName') ? $sheet->getName() : null);

                $sheets[] = [
                    'name' => $sheetName ?: 'Sheet ' . $sheet->getParent()->getIndex($sheet),
                    'headers' => $headers,
                    'rows' => $rows,
                ];
            }
        }

        return $sheets;
    }

    private function normalizeCellValue($value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_scalar($value) || $value === null) {
            return $value;
        }

        return json_encode($value);
    }

    private function normalizeHeaders(array $rawHeaders): array
    {
        $normalized = [];
        $occurrences = [];

        foreach ($rawHeaders as $index => $header) {
            $label = trim((string) $header);

            if ($label === '') {
                $label = 'Kolom ' . ($index + 1);
            }

            $occurrences[$label] = ($occurrences[$label] ?? 0) + 1;

            if ($occurrences[$label] > 1) {
                $label .= ' (' . $occurrences[$label] . ')';
            }

            $normalized[$index] = $label;
        }

        return $normalized;
    }

    private function fallbackHeaders(int $columnCount): array
    {
        $headers = [];

        for ($i = 0; $i < $columnCount; $i++) {
            $headers[] = 'Kolom ' . ($i + 1);
        }

        return $headers;
    }

    public function edit(TahapAkhirUpload $tahapAkhirUpload)
    {
        return view('admin.tahap-akhir-upload.form', compact('tahapAkhirUpload'));
    }

    public function update(Request $request, TahapAkhirUpload $tahapAkhirUpload)
    {
        $validated = $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $file = $validated['excel_file'];

        if ($tahapAkhirUpload->file_path) {
            Storage::disk('public')->delete($tahapAkhirUpload->file_path);
        }

        $path = $file->store('tahap-akhir-upload', 'public');

        $tahapAkhirUpload->update([
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.tahap-akhir-upload.index')
            ->with('success', 'File berhasil diperbarui.');
    }

    public function destroy(TahapAkhirUpload $tahapAkhirUpload)
    {
        if ($tahapAkhirUpload->file_path) {
            Storage::disk('public')->delete($tahapAkhirUpload->file_path);
        }

        $tahapAkhirUpload->delete();

        return redirect()->route('admin.tahap-akhir-upload.index')
            ->with('success', 'File dihapus.');
    }

    public function download(TahapAkhirUpload $tahapAkhirUpload)
    {
        abort_unless(Storage::disk('public')->exists($tahapAkhirUpload->file_path), 404);

        return Storage::disk('public')->download($tahapAkhirUpload->file_path, $tahapAkhirUpload->original_name);
    }
}
