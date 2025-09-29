<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WilayahController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    public function masterWilayah()
    {
        return view('admin.master.wilayah');
    }

    public function insert(Request $request, ApiTuaGroupService $apiTuaGroupService)
    {
        $validator = Validator::make($request->all(), []);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status'  => false,
                'message' => 'Validation error.',
                'error'   => $errors
            ]);
        }
        DB::beginTransaction();
        try {

            DB::commit();
            return response()->json([
                'status'  => true,
                'message' => 'Data successfully saved',
            ]);
        } catch (\Exception $e) {
            $data = [
                'status' => 'error',
                'method' => 'insert',
                'module' => 'UsersController',
                'data'   => [
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine()
                ]
            ];
            FileLogHelper::sendLogMessage($data);
            TelegramLogHelper::sendLogMessage($data);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to view data',
                'error'   => 'An internal error occurred. Check the log file: '
            ]);
        }
    }
}
