<?php

namespace App\Http\Controllers;

use Request;
use App\Bill;
use App\Wagon;
use App\Zuuch;
use App\Freight;
use App\User;
use App\Stlimit;
use App\Lombo;
use App\Transporters;
use Carbon\Carbon;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    

        $from = DB::select('select distinct fromstname, fromstcode from OZ_IDX_X_BILL t where t.FROMSTNAME is not null order by fromstname');
         $to= DB::select('select distinct  tostname, tostcode from OZ_IDX_X_BILL t where t.TOSTNAME is not null order by tostname');
        $query = "";
         $wagno= Request::input('frieght');
        $bill= Request::input('billno');
        $sdate= Carbon::today()->subDays(5)->format('Y/m/d');
        $fdate=  Carbon::today()->format('Y/m/d');
         $tost= 0;
        $fromst=0;
        $type= 0;

           
          if ($sdate !=0 && $sdate && $fdate !=0 && $fdate !=NULL) {
             $query.=" and LOADDATE between '".$sdate."' and '".$fdate."'";
      
         }
         else 
         {
             $query.=" and LOADDATE between '".date( "Y/m/d", strtotime( "-1 day" ) )."' and '".date( "Y/m/d", strtotime( "+1 day" ) )."'";
         }
     
        $result= DB::select('select t.* , f.WAGNO from OZ_IDX_X_BILL t left join OZ_IDX_X_WAGON f on f.bid = t.bid where 1=1 '.$query. '  and t.tostcode =010157 and  rownum <= 4000 order by t.loaddate desc');
         $result1=('select t.* , f.WAGNO from OZ_IDX_X_BILL t left join OZ_IDX_X_WAGON f on f.bid = t.bid where t.tostcode =010157 and rownum <= 4000'.$query. 'order by t.loaddate desc');

          $count = DB::select('select count(*) as cnt from ( '.$result1.' ) ');     


        return view('home',['result'=> $result,'from'=> $from, 'to'=> $to, 'count' => $count, 'wagno' => $wagno, 'bill' => $bill, 'fdate' => $fdate, 'sdate' => $sdate, 'tost' => $tost, 'fromst' => $fromst, 'type' => $type]);
    }
     public function search()
    {    
         $from = DB::select('select distinct fromstname, fromstcode from OZ_IDX_X_BILL t  where t.FROMSTNAME is not null order by fromstname');
         $to= DB::select('select distinct  tostname, tostcode from OZ_IDX_X_BILL t  where t.TOSTNAME is not null order by tostname');
        $query = "";
        $wagno= Request::input('frieght');
        $bill= Request::input('billno');
        $fdate= Request::input('endDate');
        $sdate= Request::input('startDate');
         $tost= '010157';
        $fromst= Request::input('fromst');
        $type= Request::input('type');
        
      
         if ($bill!=NULL) {
             $query.=" and billno like '%".$bill."%'";
         }
         else 
         {
             $query.=" ";
         }
         if ($fromst !=0 && $fromst!=NULL) {
             $query.=" and fromstcode= '".$fromst."'";
         }
         else 
         {
             $query.=" ";
         }
         if ($tost !=0 && $tost!=NULL) {
             $query.=" and tostcode= '".$tost."'";
         }
         else 
         {
             $query.=" ";
         }
         if ($type == 1) {
             $query.=" and torailcode = 31";
         }
           if ($type == 2) {
             $query.=" and torailcode <> 31";
         }
         else 
         {
             $query.=" ";
         }
   
         if ($wagno!=NULL) {
             $query.=" and wagno LIKE '%".$wagno."%'";
         }
          else 
         {
             $query.=" ";
         }
         
         if ($sdate !=0 && $sdate && $fdate !=0 && $fdate !=NULL) {
             $query.=" and LOADDATE between '".$sdate."' and '".$fdate."'";
      
         }
         else 
         {
             $query.=" ";
         }
    
        $result= DB::select('select t.* , f.WAGNO from OZ_IDX_X_BILL t left join OZ_IDX_X_WAGON f on f.bid = t.bid where rownum <= 4000'.$query. 'order by t.loaddate desc');

        $result1=('select t.* , f.WAGNO from OZ_IDX_X_BILL t left join OZ_IDX_X_WAGON f on f.bid = t.bid where rownum <= 4000'.$query. 'order by t.loaddate desc');


          $count = DB::select('select count(*) as cnt from ( '.$result1.' ) ');      
    
        return view('home',['result'=> $result,'from'=> $from, 'to'=> $to, 'bill'=> $bill, 'count' => $count, 'wagno' => $wagno, 'bill' => $bill, 'fdate' => $fdate, 'sdate' => $sdate, 'tost' => $tost, 'fromst' => $fromst, 'type' => $type]);
    }
      public function welcome()
    {
       
        return view('welcome');
    }
        public function hyanalt($id)
    {
       $hyanalt=Bill::where('bid', '=', $id)->get();
       $wagon=Wagon::where('bid', '=', $id)->get();
       $zuuch=Zuuch::where('bid', '=', $id)->get();
       $stlimit1=Stlimit::where('bid', '=', $id)->where('limittype', '=', 2)->get();
       $stlimit=Stlimit::where('bid', '=', $id)->get();
       $lombo=Lombo::where('bid', '=', $id)->get();
       $railway = DB::table('railway')->get();
       $frieght=Freight::where('bid', '=', $id)->get();
       $transporter=transporters::where('bid', '=', $id)->get();
     
       $frieghtsum = DB::table('OZ_IDX_X_FRIEGHT')
            ->where('bid', '=', $id)
            ->sum('brutto');
        
        return view('hyanalt',['hyanalt'=> $hyanalt,'wagon'=> $wagon,'zuuch'=> $zuuch,'frieght'=> $frieght,'frieghtsum'=> $frieghtsum,'lombo'=> $lombo,'stlimit'=> $stlimit,'stlimit1'=> $stlimit1,'transporter'=> $transporter,'railway'=> $railway]);
    }
     
}
