<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateStickersJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class StickerController extends Controller
{

    const PDF_SIZE = [0, 0, 82.2, 175.7];
    const PDF_TYPE = 'landscape';

    private function generateQrCodeAndUUIDs($n_stickers, $study_id, $group_id){
        $uuids_qr_codes = [];

        for($i = 0; $i < $n_stickers; ++$i){
            # Generate UUID
            $uuid = Str::uuid();
            # Build qr code
            $qr_content = [
                'study_id' => $study_id,
                'group_id' => $group_id,
                'uid' => $uuid
            ];
            $qr_code = QrCode::size(100)->generate(json_encode($qr_content));
            $qr_code_html = '<img src="data:image/svg+xml;base64,'.base64_encode($qr_code).'"  width="73.7" height="73.7" style="float:left" />';
            $uuids_qr_codes[] = [$uuid, $qr_code_html];
        }

        return $uuids_qr_codes;
    }

    public function downloadView(Request $request)
    {
        ini_set('max_execution_time', '300');
        
        $n_stickers = $request->n_stickers;
        $group_id = $request->group_id;
        $study_id = Config::get('app.study_id');

        $uuids_qr_codes = $this->generateQrCodeAndUUIDs($n_stickers, $study_id, $group_id);

        $html = view('stickers.pdfview', 
        [
            'n_stickers' => $n_stickers, 
            'group_id' => $group_id, 
            'study_id' => $study_id, 
            'uuids_qr_codes' => $uuids_qr_codes
        ])->render();
        $pdf = PDF::loadHTML($html);
        $pdf = $pdf->setPaper(self::PDF_SIZE, self::PDF_TYPE);
        $download = $pdf->download('pdfview.pdf');
        return $download;
    }
}