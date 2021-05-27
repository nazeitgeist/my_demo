<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeveloperController extends Controller
{
    public function index()
    {
        $developers = Developer::all()->toArray();
		
		$array = array();
		foreach ($developers as $key => $value) {
			if($developers[$key]['avatar'] != null){
				$developers[$key]['avatar_url'] = "storage" . $developers[$key]['avatar'];
			} else {
				$developers[$key]['avatar_url'] = "no-image.png";
			}
			$array[$key] = $developers[$key];
		}
        return array_reverse($array);
    }

    // add Developer
    public function add(Request $request)
    {
		$name = null;

		if($request->file('avatarImg')){
			$file = $request->file('avatarImg');
			$name = '/avatars/' . uniqid() . '.' . $file->extension();
			$file->storePubliclyAs('public', $name);
		} 

        $developer = new Developer([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phoneNo' => $request->phoneNo,
            'address' => $request->address,
            'avatar' => $name,

        ]);
        $developer->save();

        return response()->json('Developer successfully added');
    }

    // edit Developer
    public function edit($id)
    {
        $developer = Developer::find($id);
        return response()->json($developer);
    }

    // update Developer
    public function update($id, Request $request)
    {
        $developer = Developer::find($id);
        $developer->update($request->all());

		if($developer->avatar && $request->file('avatarImg')){
			//delete old pic
			$path = public_path("storage" . $developer->avatar);
			if(is_file($path)){
				File::delete($path);
			}
			
			//add new pic
			$file = $request->file('avatarImg');
			$name = '/avatars/' . uniqid() . '.' . $file->extension();
			$file->storePubliclyAs('public', $name);
			Developer::where('id', $id)->update(array('avatar' => $name));
		} else {
			Developer::where('id', $id)->update(array('avatar' => null));
		}
		
        return response()->json('Developer successfully updated');
    }

    // delete Developer
    public function delete($id)
    {
		$json = "";
        $developer = Developer::find($id);
		$developer->delete();
		$path = public_path("storage" . $developer->avatar);
		echo $path;
		if(is_file($path)){
			//delete file
			$success = File::delete($path);

			if($success){
				$json = response()->json('File successfully deleted');
			} else {
				$json = response()->json('Error deleting file');
			}
		} else{
			$json = response()->json('File does not exist');
			echo "";
		}

        return $json;
    }

	public function deleteMultitple(Request $request)
    {
		$json = "";
		foreach ($request['checked'] as $id) {
			$developer = Developer::find($id);
			$path = public_path("storage" . $developer->avatar);
			if(is_file($path)){
				//delete file
				$success = File::delete($path);
	
				if($success){
					$json = response()->json('File successfully deleted');
				} else {
					$json = response()->json('Error deleting file');
				}
			} else{
				$json = response()->json('File does not exist');
				echo "";
			}
		}

		Developer::whereIn("id",$request['checked'])->delete(); 

        return response()->json($json);
    }
}

