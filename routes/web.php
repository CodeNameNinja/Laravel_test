<?php

use Illuminate\Support\Facades\Route;
use App\Post;
use App\Role;
use App\Photo;
use App\Country;
use App\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
RAW SQL QUERIES
*/
// INSERT
Route::get("/insert", function(){
    $result = DB::insert('INSERT INTO posts(title,content) VALUES(?, ?)', ['PHP is awesome','I love laravel its a really awesome framework']);
    return "Inserted " . $result. " Row.";
});
//END_OF_INSERT

// START_READ
Route::get("/read/{id}", function($id){
    $results = DB::select('SELECT * FROM posts WHERE id = ?', [$id]);
    foreach($results as $post){
        return $post->title;
    }
});
//END_OF_READ

//START_OF_UPDATE
Route::get("/update/{id}", function($id){
    $updated = DB::update('UPDATE posts SET title="Updated title" WHERE id = ?', [$id]);
    return "Updated Row: ". $updated;
});
//END_OF_UPDATE

//START_OF_DELETE
Route::get("/delete/{id}",function($id){
    $result = DB::delete("DELETE FROM posts WHERE id = ?", [$id]);
    return "Deleted Row: " . $result;
});
//END_OF_DELETE

/*
Eloquent ORM
*/

Route::get("find", function(){
    $posts = Post::all();
    foreach($posts as $post){
        echo $post->title . "<br>" ;
    }
});
Route::get("find/{id}", function($id){
    $post = Post::find($id);
    return $post->title ;

});
Route::get("find-admin/{val}/{many}", function($val, $many){
    $posts = Post::where('is_admin',$val)->orderBy('id','desc')->take($many)->get();
    foreach($posts as $post){
        echo $post->title . " : " . $post->content . "<br>";
    }

});

Route::get("eloquent/insert", function(){
    $post = new Post;
    $post->title = "New Eloquent title";
    $post->content = "Wow Eloquent is really awesome.";

    $post->save();
});

Route::get("eloquent/update/{id}", function($id){
    $post = Post::find($id);
    $post->title = "New Eloquent title";
    $post->content = "Wow Eloquent is really awesome.";

    $post->save();
});

Route::get("eloquent/create", function(){
    Post::create(["title" => "New Ele Title", "content" => "Learning how to create posts with Eloquent"]);

});

Route::get("eloquent/update/{id}", function($id){
    Post::where('id', $id)->where('is_admin', 0)->update(['title'=>'Updated Title with Eloquent', 'content' => 'I Love Laravel, and I think it is an awesome framework.']);

});

Route::get("eloquent/delete/{id}",function($id){
    Post::find($id)->delete();
});
Route::get("eloquent/destroy/{id}",function($id){
     Post::destroy($id);
});

Route::get('/about/{id}', "PostController@index");

//Eloquent Relationships

//ONE TO ONE RELATIONSHIP
Route::get('/user/{id}/post', function($id){
    return User::find($id)->post;
});

//INVERSE
Route::get('/post/{id}/user', function($id){
    return Post::find($id)->user->name;
});
//One to many relationship
Route::get('/posts/{id}', function($id){
    $user = User::find($id);
     foreach($user->posts as $post){
        echo $post->title ."<br>";
     }
});
//Many to Many Relationship

Route::get('/user/{id}/role', function($id){
    $user = User::find($id)->roles()->orderBy("id","desc")->get();
    return $user[0]->name;

});

Route::get('role/{id}/users', function($id){
$users = Role::find($id)->users;
foreach($users as $user){
    echo $user->name;
}
});
//Accessing the intermediate table / pivot
Route::get("user/{id}/pivot",function($id){
    $user = User::find(1);
    foreach($user->roles as $role){
        echo $role->pivot;
    }
});

Route::get('/user/country/{id}', function($id){
    $country = Country::find($id);

    foreach($country->posts as $post){
        echo $post;
    }
});
//Polymorphic Relations

Route::get('/user/{id}/photos',function($id){
    $user = User::find($id);
    foreach($user->photos as $photo){
        echo $photo;
    }
});

//Polymorphic Inverse
Route::get('photo/{id}/post', function($id){
    $photo = Photo::findOrFail($id);
    return $photo->imageable;
});

// Forms & Validation

Route::resource('/post', 'PostController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
