<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;

class HealthCheckController extends Controller
{
    static function printHeader($message) {
      echo "<hr/><span style='color:#000;'>{$message}</span><hr/>";
    }
    
    static function printNormal($message) {
      echo "<span style='color:#000;'>{$message}</span><br/>";
    }
    
    static function printSuccess($message) {
      echo "<span style='color:#0C0;'>{$message}</span><br/>";
    }
    
    static function printError($message) {
      echo "<span style='color:#C00;'>{$message}</span><br/>";
    }
    
    public function index(Request $request) {
      if (env('APP_BUILD') == 'master') {
        // master specific health check
      }
      
      $result = DB::select('SHOW TABLES');
      if (empty($result)) {
        abort(503);
      }
      
      response('', 200);
    }
    
    public function extensive(Request $request) {
      if (!in_array(getenv('REMOTE_ADDR'), ['127.0.0.1', '::1'])) {
        response('', 200);
        exit;
      }
      
      self::printHeader("- ENVIRONMENT CHECK -");
      self::printNormal('ENV: ' . env('APP_ENV'));
      self::printNormal('BUILD: ' . env('APP_BUILD'));
      
      self::printHeader("- CONFIG CHECK -");
      try {
        DB::select('DESC customers');
        DB::select('DESC customers_encrypted');
        DB::select('DESC users');
        
        self::printSuccess('DB: Structure OK');
      } catch (\Illuminate\Database\QueryException $e) {
        self::printError('DB: Structure Invalid');
      }
      
      if (env('APP_BUILD') == 'master') {
        self::printHeader("- MASTER CHECK -");
        // master specific health check
        
        if (env('MAIL_DRIVER') == 'smtp') {
          $f = @fsockopen(env('MAIL_HOST'), env('MAIL_PORT')) ;
          if ($f !== false) {
            $res = fread($f, 1024) ;
            if (strlen($res) > 0 && strpos($res, '220') === 0) {
              self::printSuccess('SMTP: OK');
            } else {
              self::printError('SMTP: Unable to access');
            }
            fclose($f);
          } else {
            self::printError('SMTP: Unable to access');
          }
        }
      }
      
      if (env('APP_BUILD') == 'kiosk') {
        self::printHeader("- KIOSK CHECK -");
        // kiosk specific health check
      }
      
      response('', 200);
    }
}
