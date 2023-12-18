<?php

namespace App\Http\Requests;

use Carbon\Carbon;

use App\Models\Area;
use App\Models\Limit;
use App\Models\Command;
use App\Models\Connection;
use App\Models\Time_Interval;
use App\Models\Importation_Demand;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInformation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_db' => 'required',
            'area' => 'required',
            'date' => 'nullable|date_format:Y-m-d',
            'hour' => 'nullable|date_format:H:i',
        ];
    }

    public function messages()
    {
        return [
            'name_db.required' => 'El name_db es requerido',
            'area.required'    => 'El area es requerida',
            'date.date_format' => 'Formato :attribute incorrecto (yyyy-mm-dd).',
            'hour.date_format' => 'Formato :attribute incorrecto (H:i).',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $this->keyValidation($validator);
            $this->areaValidation();
            $this->dbValidation();
            $this->limitValidation();
            $this->executingImportValidation();
            $this->timeValidator();
        });
    }

    // Valida que los atributos o keys sean completos
    protected function keyValidation($validator)
    {
        $count = count($this->all());

        if ($count !== 4) {
            $validator->errors()->add('count', 'El número de atributos no es correcto');
        }

        $keys = ['name_db', 'area', 'date', 'hour'];

        foreach ($this->all() as $key => $value) {
            if (!in_array($key, $keys)) {
                $validator->errors()->add($key, 'El atributo ' . $key . ' es incorrecto');
            }
        }

        if ($validator->errors()->any()) {
            throw new HttpResponseException(response()->json([
                'status' => 422,
                'response' => $validator->errors()->first(),
            ], 422));
        }
    }

    // Valida que exista el area
    protected function areaValidation(){
        $areas = Area::where('state', 1)->pluck('name')->toArray();
        if (!in_array($this->area, $areas)) {
            throw new HttpResponseException(response()->json([
                'status' => 401,
                'response' => 'El area '.$this->area.' no existe'
            ], 401));
        }
    }

    // Valida que exista la BD
    protected function dbValidation(){
        $connection = Connection::forNameDB($this->name_db)->first();
        if(!$connection) {
            throw new HttpResponseException(response()->json([
                'status' => 401,
                'response' => 'La base de datos '.$this->name_db.' no existe'
            ], 401));
        }
    }

    // Valida que no supere el numero de importaciónes permitidos por dia
    protected function limitValidation(){
        
        $limit = Limit::getLimit($this->name_db, $this->area)->value('number');
        if(empty($limit)){
            throw new HttpResponseException(response()->json([
                'status' => 401,
                'response' => 'El usuario '.$this->name_db.' no tiene limite de importaciones programadas.'
            ], 401));
        }

        $NumberOfAttemptsPerDay = Importation_Demand::numberOfAttemptsPerDay($this->name_db, $this->area);

        if($NumberOfAttemptsPerDay >= $limit){
            throw new HttpResponseException(response()->json([
                'status' => 429,
                'response' => 'Ya superaste el limite de importaciones por dia.'
            ], 429));
        }
    }

    // Valida que una importacion no se este ejecutando
    protected function executingImportValidation(){
        $importation = Command::forNameBD($this->name_db, $this->area)->first();
        if(empty($importation)){
            throw new HttpResponseException(response()->json([
                'status'   => 401, 
                'response' => 'El area '.$this->area.' no esta habilitada para este usuario.'
            ], 401));
        }
        if($importation->state == '2'){
            throw new HttpResponseException(response()->json([
                'status'   => 409, 
                'response' => 'Ya tienes una importación en curso.'
            ], 409));
        }
    }

    public function timeValidator(){

        if ($this->date === '') {
            $dateUser = Carbon::now()->toDateString();
        } else {
            $dateUser = Carbon::parse($this->date)->toDateString();
        }

        if ($this->hour === null) {
            $hourUser = Carbon::now()->toTimeString();
        } else {
            $hourUser = Carbon::parse($this->hour)->toTimeString();
        }
   
        $currentHour = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        
        //Primero se calcula la diferencia entre la hora y fecha actual y la que ingresa el usuario
        if ($dateUser < $currentDate) {
            throw new HttpResponseException(response()->json([
                'status'   => 401, 
                'response' => 'La importación no puede ejecutarse el dia '.$dateUser.'. Por favor selecciona otra fecha.'
            ], 401));
        }

        if ($hourUser < $currentHour && $dateUser == $currentDate) {
            throw new HttpResponseException(response()->json([
                'status'   => 401, 
                'response' => 'La importación no puede ejecutarse a las '.$hourUser.'. Por favor selecciona otra hora.'
            ], 401));
        }

        // Algoritmo para calcular si se puede realizar una importacion en la fecha seleccionada por el usuario
        $timeInterval = Time_Interval::getInterval($this->name_db, $this->area)->value('time');
        $processAndRunning = Importation_Demand::processAndRunning($this->name_db, $this->area, $timeInterval)->get();
        if(isset($processAndRunning)){
            foreach ($processAndRunning as $data) {
                $datetimeData = Carbon::parse($data->date.' '.$data->hour, 'UTC');
                $datetimeUser = Carbon::parse($dateUser.' '.$hourUser, 'UTC' );

                $differenceInMinutes = $datetimeUser->diffInMinutes($datetimeData);
                if($differenceInMinutes <= $timeInterval){
                    throw new HttpResponseException(response()->json([
                        'status'   => 401, 
                        'response' => 'La importación no puede ejecutarse en la fecha '.$dateUser.' a las '.$hourUser.'. Por favor selecciona otra hora o fecha.'
                    ], 401));
                }
            }
        }

        return [
            'hourUser' => $hourUser,
            'dateUser' => $dateUser
        ];
    }
}
