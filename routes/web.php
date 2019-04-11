<?php

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

Route::get('/', 'CustomLoginController@index')->middleware('customRedirectIfNotAuthenticated', 'customRedirectIfAuthenticated');

Route::get('/login', 'CustomLoginController@index')->middleware('customRedirectIfAuthenticated');

Route::get('/logout', 'CustomLoginController@logout');

Route::post('post-login', 'CustomLoginController@postLogin');

Route::any('/dashboard', 'DashboardController@index')->middleware('customRedirectIfNotAuthenticated','menu');

Route::group(['prefix' => 'master', 'middleware' => ['customRedirectIfNotAuthenticated', 'menu']], function() {
    Route::group(['prefix' => 'access-control'], function() {
        Route::any('/', 'Master\AccessControlController@index');
        Route::get('update/{id}', 'Master\AccessControlController@update');
        Route::post('save', 'Master\AccessControlController@save');
    });
});

Route::group(['prefix' => 'transaction', 'middleware' => ['customRedirectIfNotAuthenticated', 'menu']], function() {
    Route::group(['prefix' => 'project'], function() {
        Route::any('/', 'Transaction\ProjectController@index');
        Route::get('add', 'Transaction\ProjectController@add');
        Route::get('detail/{id}', 'Transaction\ProjectController@detail');
        Route::get('update-detail/{id}/{section}', 'Transaction\ProjectController@updateDetail');
        Route::get('add-progress/{id}/{section}', 'Transaction\ProjectController@addProgress');
        Route::post('save-progress', 'Transaction\ProjectController@saveProgress');
        Route::post('save-milestone', 'Transaction\ProjectController@saveMilestone');
        Route::post('save-task', 'Transaction\ProjectController@saveTask');
        Route::post('delete-milestone', 'Transaction\ProjectController@deleteMilestone');
        Route::post('delete-task', 'Transaction\ProjectController@deleteTask');
    });
    Route::group(['prefix' => 'meeting', 'middleware' => ['customRedirectIfNotAuthenticated']], function() {
        Route::any('/', 'Transaction\MeetingController@index');
        Route::get('add', 'Transaction\MeetingController@add');
        Route::post('save', 'Transaction\MeetingController@save');
        Route::get('update/{id}', 'Transaction\MeetingController@update');
        Route::any('check-last-updated', 'Transaction\MeetingController@checkLastUpdated');
        Route::post('delete', 'Transaction\MeetingController@delete');
    });
    Route::group(['prefix' => 'update-progress'], function() {
        Route::any('/', 'Transaction\UpdateProgressController@index');
        Route::get('update/{id}', 'Transaction\UpdateProgressController@update');
        Route::get('view/{id}', 'Transaction\UpdateProgressController@view');
        Route::any('check-last-updated', 'Transaction\UpdateProgressController@checkLastUpdated');
        Route::post('save', 'Transaction\UpdateProgressController@save');
        Route::post('delete', 'Transaction\UpdateProgressController@delete');
    });
    Route::group(['prefix' => 'validation-progress'], function() {
        Route::any('/', 'Transaction\ValidationProgressController@index');
        Route::get('update/{id}', 'Transaction\ValidationProgressController@update');
        Route::any('check-last-updated', 'Transaction\ValidationProgressController@checkLastUpdated');
        Route::post('save', 'Transaction\ValidationProgressController@save');
    });
});

Route::group(['prefix' => 'report', 'middleware' => ['customRedirectIfNotAuthenticated', 'menu']], function() {
    Route::group(['prefix' => 'project'], function() {
        Route::any('/', 'Report\ReportProjectController@index');
        Route::get('detail/{id}', 'Report\ReportProjectController@detail');
        Route::get('show-history-project/{id}/{section}', 'Report\ReportProjectController@showHistoryProject');
        Route::get('show-history-milestone/{id}/{section}', 'Report\ReportProjectController@showHistoryMilestone');
        Route::get('show-history-task/{id}/{section}', 'Report\ReportProjectController@showHistoryTask');
    });
    Route::group(['prefix' => 'detail-history-project'], function() {
        Route::any('/', 'Report\DetailHistoryProjectController@index');
    });
    Route::group(['prefix' => 'detail-history-milestone'], function() {
        Route::any('/', 'Report\DetailHistoryMilestoneController@index');
        Route::any('/get-milestone', 'Report\DetailHistoryMilestoneController@getMilestone');
    });
});