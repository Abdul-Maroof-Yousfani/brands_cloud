<?php
//Start Users
Route::group(['prefix' => 'users','before' => 'csrf'], function () {
    Route::get('/u', 'UsersController@toDayActivity');
    Route::get('/createMainMenuTitleForm','UsersController@createMainMenuTitleForm');
    Route::get('/createSubMenuForm','UsersController@createSubMenuForm');
    Route::get('/createUsersForm', 'UsersController@createUsersForm');
    Route::get('/createRoleForm','UsersController@createRoleForm');
    Route::get('/viewRoleList','UsersController@viewRoleList');
    Route::get('/viewEmployeePrivileges/{id}','UsersController@viewEmployeePrivileges');
    Route::get('/editUserProfile','UsersController@editUserProfile');

    Route::get('/createNewUser','UsersAddDetailController@createNewUser');
    Route::post('/storeNewUser','UsersAddDetailController@storeNewUser');
    
    Route::get('/userList','UsersController@userList');
    Route::get('/userEditForm/{id}','UsersAddDetailController@userEditForm')->name('userEditForm');
    Route::post('/editUser','UsersAddDetailController@editUser');

});

Route::group(['prefix' => 'udc','before' => 'csrf'], function () {
    Route::get('/viewMainMenuTitleList','UsersDataCallController@viewMainMenuTitleList');
    Route::get('/viewSubMenuList','UsersDataCallController@viewSubMenuList');
});

Route::group(['prefix' => 'uad','before' => 'csrf'], function () {
    Route::post('/addMainMenuTitleDetail','UsersAddDetailController@addMainMenuTitleDetail');
    Route::post('/addSubMenuDetail','UsersAddDetailController@addSubMenuDetail');
    Route::post('/addRoleDetail','UsersAddDetailController@addRoleDetail');

    /*Edit Routes*/
    Route::post('/editUserPasswordDetail','UsersEditDetailController@editUserPasswordDetail');
    Route::post('/editUserRoleDetail','UsersEditDetailController@editUserRoleDetail');
    Route::post('/editApprovalCodeDetail','UsersEditDetailController@editApprovalCodeDetail');
    
    
    Route::post("/upload-profile-picture", function(\Illuminate\Http\Request $request) {
        if (!$request->hasFile('profile_pic')) {
            return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
        }

        $file = $request->file('profile_pic');
        if (!$file->isValid()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid file upload'], 400);
        }

        $user = Auth::user();

        // Ensure directory exists
        $path = public_path('uploads/profile_images');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // delete old image if exists
        if ($user->profile_image && file_exists($path . '/' . $user->profile_image)) {
            unlink($path . '/' . $user->profile_image);
        }

        // generate new name
        $newImageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // move file
        $file->move($path, $newImageName);

        // save to DB
        $user->profile_image = $newImageName;
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile Picture Updated Successfully',
            'image_url' => asset('uploads/profile_images/' . $newImageName)
        ]);
    })->name("update.profile-pic");


});
//End Users
