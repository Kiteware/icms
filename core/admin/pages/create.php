<?php defined('_ICMS') or die; ?>
<div class="box">
    <div class="box-header">Create a New Page</div>
    <div class="box-body">
        <form method="post" action="/admin/pages/create" class="no-reload-form">
            <fieldset class="form-group">
                <label for="pageTitle">Title:</label>
                <input type="text" class="form-control" name="pageTitle" id="pageTitle"/>
            </fieldset>
            <div class="row">
                <div class="col-md-4">
                    <fieldset class="form-group">
                        <label for="pageURL">URL:</label>
                        <input type="text" class="form-control" name="pageURL" id="pageURL" />
                    </fieldset>
                </div>
                <div class="col-md-4">
                    <fieldset class="form-group">
                        <label for="pagePosition">Position:</label>
                        <input type="number" class="form-control" name="pagePosition" id="pagePosition" min="1" max="10" />
                    </fieldset>
                </div>
                <div class="col-md-4">
                    <fieldset class="form-group">
                        <label for="pagePermission">Usergroups that have access:</label>
                        <select class="form-control" name="pagePermission" id="">
                            <option>guest</option>
                            <option>user</option>
                            <option>administrator</option>
                        </select>
                    </fieldset>
                </div>
            </div>
            <fieldset class="form-group">
                <label for="pageContent">Content:</label>
                <textarea class="form-control" name="pageContent" id="pageContent"></textarea>
            </fieldset>
            <fieldset class="form-group">
                <label for="pageKeywords">Meta Keywords</label>
                <input type="text" class="form-control" name="pageKeywords" id="pageKeywords" />
            </fieldset>
            <fieldset class="form-group">
                <label for="pageDesc">Meta Description</label>
                <input type="text" class="form-control" name="pageDesc" id="pageDesc" />
            </fieldset>
            <button type="submit" name="submit" class="btn btn-primary">Create Page</button>
        </form>
    </div>
</div>
