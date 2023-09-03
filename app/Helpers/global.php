<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;

if (! function_exists('moneyFormat')) {
    /**
     * moneyFormat
     *
     * @param  mixed $str
     * @return void
     */
    function moneyFormat($str) {
        return 'Rp. ' . number_format($str, '0', '', '.');
    }
}

function makeForm(mixed $table, $value = [])
{
    $tableName = Schema::getColumnListing($table);
    $columns = array();
    $no = 0;
    foreach ($tableName as $item) {
        $columns[$no] = [
            'name' => $item,
            'type' => DB::getSchemaBuilder()->getColumnType($table, $item)
        ];
        if ($item == 'created_at' || $item == 'updated_at' || $item == 'id') {
            unset($columns[$no]);
        }
        $no++;
    }
    $data = collect($columns)->sortBy('name')->reverse()->toArray();
    $html = "";

    foreach ($data as $col) {
        $name = $col['name'];
        $val = is_array($value) && isset($value[$name]) ? $value[$name] : '';
        // return $value;
        if ($name == 'thumbnail' || $name == 'logo' || $name == 'image' || $name == 'photo') {
            $html .=
                "<div class='col-md-6'><div class='form-group'>
                <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                <input type='file' accept='image/*' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                    name='" . $name . "'>
                <img class='imageForm mt-3' src='" . $val . "' width='400' />
            </div></div>";
        } else if ($name == 'url' || $name == 'method') {
            $html .=
                "<div class='col-md-6'><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input required readonly type='text' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . $val . "'>
                </div></div>";
        } else if ($name == 'business_id') {
            $html .=
                "<div class='col-md-6' hidden><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input required readonly type='text' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . auth()->user()->business_id . "'>
                </div></div>";
        } else if ($name == 'user_id') {
            $html .=
                "<div class='col-md-6' hidden><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input required readonly type='text' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . auth()->user()->id . "'>
                </div></div>";
        } else if ($name == 'title' || $name == 'name') {
            $html .=
                "<div class='col-md-6'><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input required type='text' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . $val . "'>
                </div></div>";
        } else if ($name == 'stars') {
            $html .=
                "<div class='col-md-6'><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input required type='number' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . $val . "' max='5' min='1'>
                </div></div>";
        } else if ($name == 'caption' || $name == 'content') {
            $html .=
                    "<div class='col-md-6'><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <textarea required name='" . $name . "' id='" . $name . "' cols='30' rows='5' class='form-control'>$val</textarea>
                </div></div>";
        } else {
            if ($col['type'] == 'string') {
                $html .=
                    "<div class='col-md-6'><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input required type='text' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . $val . "'>
                </div></div>";
            } else if ($col['type'] == 'text') {
                $html .=
                    "<div class='col-md-6'><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <textarea required name='" . $name . "' id='" . $name . "' cols='30' rows='5' class='form-control'>$val</textarea>
                </div></div>";
            } else if ($col['type'] == 'datetime') {
                $html .=
                    "<div class='col-md-6'><div class='form-group'>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input required type='date' class='form-control' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . $val . "'>
                </div></div>";
            } else if ($col['type'] == 'boolean') {
                $html .=
                    "<div class='col-md-6'><div class='form-group' hidden>
                    <label for='" . $name . "' class='form-label'> " . ucfirst(str_replace("_", " ", $name)) . " </label>
                    <input type='text' hidden value='true' class='' id='" . $name . "' aria-describedby='" . $name . "Help' autocomplete='off'
                        name='" . $name . "' value='" . $val . "'>
                </div></div>";
            }
        }
    }

    // dd($columns);
    return $html;
}

function getTable(mixed $table)
{
    $columns = Schema::getColumnListing($table);
    $name = [];
    $no = 0;
    foreach ($columns as $item) {
        $name[$no] = [
            "title" => ucfirst(str_replace("_", " ", $item)),
            "name" => $item,
            'type' => DB::getSchemaBuilder()->getColumnType($table, $item)
        ];
        if ($item == 'created_at' || $item == 'updated_at' || $item == 'id' || substr($item, -3) == '_id') {
            unset($name[$no]);
        }
        $no++;
    }
    $data = collect($name)->sortBy('name')->reverse()->toArray();
    return $data;
}

function getArrayPost($request, $table)
{
    $array = $request->all();
    foreach (getTable($table) as $item) {
        $tableName = $item['name'];
        if ($tableName == 'image' || $tableName == 'thumbnail' || $tableName == 'logo' || $tableName == 'photo') {
            if (isset($request->$tableName)) {
                if ($request->_method == 'POST') {
                    $request->validate([
                        $tableName => 'required|mimes:jpg,bmp,png',
                    ]);
                }
                $nm = $request->$tableName;
                $namafile = Str::slug(isset($request->title) ? $request->title : $request->name) . ".png";
                $nm->move(public_path() . '/' . $table, $namafile);
                $linkImage = url('/') . '/' . $table . '/' . $namafile;
                $array[$tableName] = $linkImage;
            }
            else {
                if ($request->_method == 'POST') {
                    return Alert::toast($item['title'] . ' is required !', 'error');
                }
            }
        } else if ($tableName == 'end_at' || $tableName == 'start_at') {
            $array[$tableName] = Carbon::parse($request->$tableName);
            $request->validate([
                $tableName => 'required',
            ]);
        } else if ($tableName == 'status' && $item['type'] == 'boolean') {
            $array[$tableName] = true;
        } else {
            $array[$tableName] = $request->$tableName;
            $request->validate([
                $tableName => 'required',
            ]);
            Alert::toast('Data berhasil disimpan', 'success');
        }
    }
    return $array;
}
