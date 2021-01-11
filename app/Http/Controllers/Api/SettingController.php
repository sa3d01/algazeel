<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class SettingController extends MasterController
{
    protected $model;
    protected $auth_key;

    public function __construct(Setting $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function check(){
        return $this->sendResponse('false');
    }

    public function index(){
//        if (!file_exists('/home/rowadtqnee/public_html/saadCopy'))
//        {
//            mkdir('/home/rowadtqnee/public_html/saadCopy', 0777, true);
//        }
        $setting=Setting::first();
        $data=[];
        $data['about']=$setting->about['user'];
        $data['licence']=$setting->licence['user'];
        $data['twitter']=$setting->socials['twitter'];
        $data['snap']=$setting->socials['snap'];
        $data['instagram']=$setting->socials['instagram'];
        if (auth()->user()){
            if (auth()->user()->user_type->name=='user'){
                $data['licence']=$setting->licence['user'];
            }else{
                $data['licence']=$setting->licence['provider'];
            }
        }
        return $this->sendResponse($data);
    }
//    public function test(){
//        $user=User::find(51);
//        $token=auth()->login($user);
//        if($token){
//            $authed_user=auth()->user();
////            $authed_user->tokens->each(function($token, $key) {
////                $token->delete();
////            });
//        } else{
//            return '401';
//        }
//    }
//    public function our_backup_database(){
//
//        //ENTER THE RELEVANT INFO BELOW
//        $mysqlHostName      = env('DB_HOST');
//        $mysqlUserName      = env('DB_USERNAME');
//        $mysqlPassword      = env('DB_PASSWORD');
//        $DbName             = env('DB_DATABASE');
//        $backup_name        = "mybackup.sql";
//        $tables             = array("users","messages","posts");
//
//        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
//        $get_all_table_query = "SHOW TABLES";
//        $statement = $connect->prepare($get_all_table_query);
//        $statement->execute();
//        $result = $statement->fetchAll();
//
//
//        $output = '';
//        foreach($tables as $table)
//        {
//            $show_table_query = "SHOW CREATE TABLE " . $table . "";
//            $statement = $connect->prepare($show_table_query);
//            $statement->execute();
//            $show_table_result = $statement->fetchAll();
//
//            foreach($show_table_result as $show_table_row)
//            {
//                $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
//            }
//            $select_query = "SELECT * FROM " . $table . "";
//            $statement = $connect->prepare($select_query);
//            $statement->execute();
//            $total_row = $statement->rowCount();
//
//            for($count=0; $count<$total_row; $count++)
//            {
//                $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
//                $table_column_array = array_keys($single_result);
//                $table_value_array = array_values($single_result);
//                $output .= "\nINSERT INTO $table (";
//                $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
//                $output .= "'" . implode("','", $table_value_array) . "');\n";
//            }
//        }
//        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
//        $file_handle = fopen($file_name, 'w+');
//        fwrite($file_handle, $output);
//        fclose($file_handle);
//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename=' . basename($file_name));
//        header('Content-Transfer-Encoding: binary');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//        header('Content-Length: ' . filesize($file_name));
//        ob_clean();
//        flush();
//        readfile($file_name);
//        unlink($file_name);
//    }
//    public function exportCsv(Request $request)
//    {
//        $fileName = 'tasks.csv';
//        $tasks = Task::all();
//
//        $headers = array(
//            "Content-type"        => "text/csv",
//            "Content-Disposition" => "attachment; filename=$fileName",
//            "Pragma"              => "no-cache",
//            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
//            "Expires"             => "0"
//        );
//
//        $columns = array('Title', 'Assign', 'Description', 'Start Date', 'Due Date');
//
//        $callback = function() use($tasks, $columns) {
//            $file = fopen('php://output', 'w');
//            fputcsv($file, $columns);
//
//            foreach ($tasks as $task) {
//                $row['Title']  = $task->title;
//                $row['Assign']    = $task->assign->name;
//                $row['Description']    = $task->description;
//                $row['Start Date']  = $task->start_at;
//                $row['Due Date']  = $task->end_at;
//
//                fputcsv($file, array($row['Title'], $row['Assign'], $row['Description'], $row['Start Date'], $row['Due Date']));
//            }
//
//            fclose($file);
//        };
//
//        return response()->stream($callback, 200, $headers);
//    }
}
