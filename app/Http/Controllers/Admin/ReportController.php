<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Session;
use Excel;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware(function ($request, $next) {
          if ($request->isMethod('post')) {
            $filteredRequest = $request->only(['fromdate', 'todate', 'daterange']);
            
            // try to get from and to from daterange first (format are YYYY-MM-DD - YYYY-MM-DD)
            if (preg_match('/^\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}$/', $filteredRequest['daterange'])) {
              $fromdate = new \DateTime(substr($filteredRequest['daterange'], 0, 10));
              $fromdate = $fromdate->format('Y-m-d H:i:s');
            
              // this guarantee we have + 1 day - 1 second period of fromdate and todate
              $todate = new \DateTime(substr($filteredRequest['daterange'], -10));
              $todate = $todate->add(new \DateInterval('P1D'));
              $todate = $todate->sub(new \DateInterval('PT1S'));
              $todate = $todate->format('Y-m-d H:i:s');
            
              $request->merge(['fromdate' => $fromdate, 'todate' => $todate]);
            }
            
            // override from and to from it's actual parameter, for API backward compatibility
            $filteredRequest['fromdate'] = new \DateTime($filteredRequest['fromdate']);
            $filteredRequest['fromdate'] = $filteredRequest['fromdate']->format('Y-m-d H:i:s');
            
            // this guarantee we have + 1 day - 1 second period of fromdate and todate
            $filteredRequest['todate'] = new \DateTime($filteredRequest['todate']);
            $filteredRequest['todate'] = $filteredRequest['todate']->add(new \DateInterval('P1D'));
            $filteredRequest['todate'] = $filteredRequest['todate']->sub(new \DateInterval('PT1S'));
            $filteredRequest['todate'] = $filteredRequest['todate']->format('Y-m-d H:i:s');
            
            $request->merge($filteredRequest);
          }
          
          return $next($request);
        });
    }
    
    public function getReportTypes() {
      $allFiles = array_filter(Storage::disk('sqlbank')->files('.'), function ($file) {
        return preg_match('/^[\w_\-\(\)]+.sql$/U', $file);
      });
      
      $allReports = [];
      foreach ($allFiles as $file) {
        $allReports[basename($file, '.sql')] = $this->pretifyReportName(basename($file, '.sql'));
      }
      
      return $allReports;
    }
    
    function pretifyReportName($reportName) {
      return ucwords(str_replace(['-', '_'], ' ', $reportName));
    }
  
    public function generateStandardReport(Request $request, $reportName, $exportType) {
      $prettyReportName = $this->pretifyReportName($reportName);
      if (!empty($exportType) && !in_array($exportType, ['json', 'csv', 'xls', 'xlsx'])) {
        $exportType = 'json';
      }
    
      // throw 404 if report not found
      if (!Storage::disk('sqlbank')->exists($reportName . '.sql')) {
        abort(404, 'Page Not Found');
      }
    
      $query = Storage::disk('sqlbank')->get($reportName . '.sql');
      $betweenDate = $request->only(['fromdate', 'todate']);
      $parameters = array_merge(array_values($betweenDate), array_values($betweenDate));
    
      $report = DB::select($query, $parameters);
      $report = json_decode(json_encode($report), true);
      
      if (empty($exportType)) {
        return $report;
      }
      
      if ($exportType == 'json') {
        return response()->json($report);
      }
    
      Excel::create($prettyReportName, function($excel) use ($prettyReportName, $report) {
        $excel->setTitle("CAG Report");

        $excel->sheet("CAG Report", function($sheet) use ($report) {
          // ->fromArray($source, $nullValue, $startCell, $strictNullComparison, $headingGeneration)
          $columnNameList = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
          $sheet->fromArray($report, null, $columnNameList[0] . '1', true, true);
        
        });
      })->store('csv')->export($exportType);
    }

    /**
     * show report form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('admin.report_form', ['reportTypes' => $this->getReportTypes()]);
    }
    
    /**
     * generate report.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
      if (in_array($request->input('type'), ['csv', 'xlsx'])) {
        $this->generateStandardReport($request, $request->input('id'), $request->input('type'));
      }
      
      $report = $this->generateStandardReport($request, $request->input('id'), null);
      return view('admin.report_form', ['reportTypes' => $this->getReportTypes(), 'report' => $report]);
    }
}