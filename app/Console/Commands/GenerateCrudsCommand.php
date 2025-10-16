<?php

namespace App\Console\Commands;

use Directory;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Schema;
use Storage;
use Str;

class GenerateCrudsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud-all {folder} {tables*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute all sub folder for migrate';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Generating CRUD-s  started");

        $folder = $this->argument(('folder'));
        $tableNames = \DB::select('SHOW TABLES');
        $tableNames = array_map('current', $tableNames);

        $tableNames = $this->argument('tables');

        foreach ($tableNames as $table) {
            //dd($added);
            $entity = $this->dashesToCamelCase($table, true);
            $entityInCamelCase = Str::singular($entity);
            //dd($entity);

            //dd(Str::singular($entity));
            $entityInCamelCaseFirstNoCapitalize = substr($this->dashesToCamelCase($table, false), 0, strlen($this->dashesToCamelCase($table, false)) - 1);

            $entityControllerName = $entityInCamelCase . 'Controller';
            $entityRepositoryName = $entityInCamelCase . 'Repository';
            $entityRepositoryNameFirstNoCapitalize = $this->dashesToCamelCase($table, false) . 'Repository';
            $entityNameFirstNoCapitalize = $this->dashesToCamelCase($table, false) . 'Repository';
            $columns = Schema::getColumns($table);

            $insertRequest = "<?php

namespace App\Http\Requests\\{$folder}\\{$entityInCamelCase};

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class {$entityInCamelCase}InsertRequest extends FormRequest
{
    /* Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /* Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [";

            foreach ($columns as $column) {
                $end = substr($column['name'], strlen($column['name']) - 3, 3);
                $denyColomns = ['created_at','updated_at','deleted_at','created_by_user_id','updated_by_user_id','uuid'];
                if ($column['name'] != 'id' && !in_array($column['name'], $denyColomns)) {
                    $required = 'required';
                    if ($column['nullable'] == true) {
                        $required = 'nullable';
                    }
                    $columnType = 'integer';
                    if ($column['type_name'] == 'bigint') {
                        $columnType = 'integer';
                    }
                    if ($column['type_name'] == 'date') {
                        $columnType = 'date';
                    }
                    if ($column['type_name'] == 'numeric') {
                        $columnType = 'numeric';
                    }
                    if ($column['type_name'] == 'json') {
                        $columnType = 'array';
                    }
                    if ($column['type'] == 'tinyint(1)') {
                        $columnType = 'boolean';
                    }
                    if ($column['type_name'] == 'char' || $column['type_name'] == 'varchar' || $column['type_name'] == 'text') {
                        $columnType = 'string';
                    }
                    $insertRequest .= "\n\t\t\t'{$column['name']}' => '{$required}|{$columnType}";
                    if ($end == '_id') {
                        //$insertRequest .= "|exists:" . substr($column['name'], 0, strlen($column['name']) - 3) . "s,id";
                    }
                    $insertRequest .= "',";
                }
            }

            $insertRequest .= "\n\t\t];
    }
}";

            $updateRequest = "<?php

namespace App\Http\Requests\\{$folder}\\{$entityInCamelCase};

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class {$entityInCamelCase}UpdateRequest extends FormRequest
{
    /* Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [";

            foreach ($columns as $column) {

                $end = substr($column['name'], strlen($column['name']) - 3, 3);
                $denyColomns = ['created_at','updated_at','deleted_at','created_by_user_id','updated_by_user_id','uuid'];
                if ($column['name'] != 'id' && !in_array($column['name'], $denyColomns)) {
                    $required = 'nullable';

                    $columnType = 'integer';
                    if ($column['type_name'] == 'bigint') {
                        $columnType = 'integer';
                    }
                    if ($column['type_name'] == 'date') {
                        $columnType = 'date';
                    }
                    if ($column['type_name'] == 'numeric') {
                        $columnType = 'numeric,2';
                    }
                    if ($column['type_name'] == 'json') {
                        $columnType = 'array';
                    }
                    if ($column['type'] == 'tinyint(1)') {
                        $columnType = 'boolean';
                    }
                    if ($column['type_name'] == 'char' || $column['type_name'] == 'varchar' || $column['type_name'] == 'text') {
                        $columnType = 'string';
                    }
                    $updateRequest .= "\n\t\t\t'{$column['name']}' => '{$required}|{$columnType}";
                    if ($end == '_id') {
                        //$updateRequest .= "|exists:" . substr($column['name'], 0, strlen($column['name']) - 3) . "s,id";
                    }
                    $updateRequest .= "',";
                }
            }

            $updateRequest .= "\n\t\t];
    }
}";

            $itemResource = "<?php

namespace App\Http\Resources\\{$folder}\\{$entityInCamelCase};

use App\Http\Resources\BaseJsonResource;

class {$entityInCamelCase}ItemResource extends BaseJsonResource
{
    public function __construct(\$item)
    {
        \$this->data = [";

            foreach ($columns as $column) {
                $itemResource .= "\n\t\t\t'{$column['name']}' => \$item['{$column['name']}'],";
            }
            $itemResource .= "\n\t\t];
    }
}
";


            $listResource = "<?php

namespace App\Http\Resources\\{$folder}\\{$entityInCamelCase};

use App\Http\Resources\BaseJsonResource;

class {$entityInCamelCase}ListResource extends BaseJsonResource
{
    public function __construct(\$data)
    {
        parent::__construct(data: \$data);
        \$this->data = [];

        foreach (\$data as \$item){
            \$this->data[] = [";
            foreach ($columns as $column) {
                $listResource .= "\n\t\t\t\t'{$column['name']}' => \$item['{$column['name']}'],";
            }
            $listResource .= "\n\t\t\t];
        }
    }
}
";

            $model = "<?php

namespace App\Models;

class {$entityInCamelCase} extends BaseModel
{
    public \$timestamps = true;

    protected \$fillable = [";

            foreach ($columns as $column) {
                $end = substr($column['name'], strlen($column['name']) - 3, 3);
                $denyColomns = ['created_at','updated_at','deleted_at'];
                if ($column['name'] != 'id' && !in_array($column['name'], $denyColomns)) {
                    $model .= "\n\t\t'{$column['name']}',";
                }
            }


            $model .= "\n\t];
}";

            $repository = "<?php

namespace App\Repositories\\{$entityInCamelCase};

use App\Models\\{$entityInCamelCase};
use App\Repositories\BaseRepository;

class {$entityInCamelCase}Repository extends BaseRepository
{
    public function __construct(public {$entityInCamelCase} \${$entityInCamelCaseFirstNoCapitalize})
    {
        parent::__construct(\${$entityInCamelCaseFirstNoCapitalize});
    }
}
";


            $controllerContent = "<?php

namespace App\Http\Controllers\\{$folder};

use App\Http\Controllers\Controller;
use App\Http\Requests\\{$folder}\\{$entityInCamelCase}\\{$entityInCamelCase}InsertRequest;
use App\Http\Requests\\{$folder}\\{$entityInCamelCase}\\{$entityInCamelCase}UpdateRequest;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\\{$folder}\\{$entityInCamelCase}\\{$entityInCamelCase}ItemResource;
use App\Http\Resources\\{$folder}\\{$entityInCamelCase}\\{$entityInCamelCase}ListResource;
use App\Repositories\\{$entityInCamelCase}\\{$entityRepositoryName};
use Illuminate\Http\Request;
use Response;

class {$entityInCamelCase}Controller extends Controller
{
    public function __construct(public $entityRepositoryName \$$entityRepositoryNameFirstNoCapitalize) {
        //
    }

    public function getAll()
    {
        return Response::apiSuccess(
            new {$entityInCamelCase}ListResource(data: \$this->{$entityRepositoryNameFirstNoCapitalize}->selectAllWithPagination())
        );
    }

    public function getById(int \$id)
    {
        return Response::apiSuccess(
            new {$entityInCamelCase}ItemResource(item: \$this->{$entityRepositoryNameFirstNoCapitalize}->selectById(\$id))
        );
    }


    public function create({$entityInCamelCase}InsertRequest \$request)
    {
        return Response::apiSuccess(
            new {$entityInCamelCase}ItemResource(item: \$this->{$entityRepositoryNameFirstNoCapitalize}->insert(\$request->validated()))
        );
    }


    public function update({$entityInCamelCase}UpdateRequest \$request, int \$id)
    {
        return Response::apiSuccess(
            new {$entityInCamelCase}ItemResource(item: \$this->{$entityRepositoryNameFirstNoCapitalize}->update(\$request->validated(), \$id))
        );
    }


    public function delete(int \$id)
    {
        \$this->{$entityRepositoryNameFirstNoCapitalize}->delete(\$id);

        return Response::apiSuccess();
    }
}
";


            $route = "<?php

use App\Http\Controllers\\{$folder}\\{$entityControllerName};
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('{$table}')->controller({$entityControllerName}::class)->middleware(['auth:api'])->group(function () {
        Route::get('/', 'getAll');
        Route::get('/{id}', 'getById');
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
    });
});";

            //dd(app_path() . "/Http/Resources/{$folder}/{$entityInCamelCase}/");
            if (!file_exists(app_path() . "/Http/Requests/{$folder}/")) {

                mkdir(app_path() . "/Http/Requests/{$folder}/");
            }

            if (!file_exists(app_path() . "/Http/Requests/{$folder}/{$entityInCamelCase}/")) {

                mkdir(app_path() . "/Http/Requests/{$folder}/{$entityInCamelCase}/");
            }
            if (!file_exists(app_path() . "/Http/Controllers/{$folder}/")) {

                mkdir(app_path() . "/Http/Controllers/{$folder}/");
            }

            if (!file_exists(app_path() . "/Http/Controllers/{$folder}/")) {

                mkdir(app_path() . "/Http/Controllers/{$folder}/");
            }
            if (!file_exists(app_path() . "/Http/Resources/{$folder}/")) {

                mkdir(app_path() . "/Http/Resources/{$folder}/");
            }
            if (!file_exists(app_path() . "/Http/Resources/{$folder}/{$entityInCamelCase}/")) {

                mkdir(app_path() . "/Http/Resources/{$folder}/{$entityInCamelCase}/");
            }
            if (!file_exists(app_path() . "/Repositories/{$entityInCamelCase}/")) {

                mkdir(app_path() . "/Repositories/{$entityInCamelCase}/");
            }

            if (!file_exists(app_path() . "/Http/Controllers/{$folder}/{$entityControllerName}.php")) {
                File::put(app_path() . "/Http/Controllers/{$folder}/{$entityControllerName}.php", $controllerContent);
            }

            if (!file_exists(app_path() . "/Http/Requests/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}InsertRequest.php")) {
                File::put(app_path() . "/Http/Requests/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}InsertRequest.php", $insertRequest);
            }

            if (!file_exists(app_path() . "/Http/Requests/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}UpdateRequest.php")) {
                File::put(app_path() . "/Http/Requests/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}UpdateRequest.php", $updateRequest);
            }

            if (!file_exists(app_path() . "/Http/Resources/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}ItemResource.php")) {
                File::put(app_path() . "/Http/Resources/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}ItemResource.php", $itemResource);
            }

            if (!file_exists(app_path() . "/Http/Resources/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}ListResource.php")) {
                File::put(app_path() . "/Http/Resources/{$folder}/{$entityInCamelCase}/{$entityInCamelCase}ListResource.php", $listResource);
            }

            if (!file_exists(app_path() . "/Models/{$entityInCamelCase}.php")) {
                File::put(app_path() . "/Models/{$entityInCamelCase}.php", $model);
            }

            if (!file_exists(app_path() . "/Repositories/{$entityInCamelCase}/{$entityInCamelCase}Repository.php")) {
                File::put(app_path() . "/Repositories/{$entityInCamelCase}/{$entityInCamelCase}Repository.php", $repository);
            }

            if (!file_exists(base_path() . "/routes/ApiRoutes/{$entityInCamelCase}Route.php")) {
                File::put(base_path() . "/routes/ApiRoutes/{$entityInCamelCase}Route.php", $route);
            }
        }



        $this->info("Generating CRUD-s finished");

        return Command::SUCCESS;
    }

    public function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {

        $str = str_replace('_', '', ucwords($string, '_'));


        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }
}
