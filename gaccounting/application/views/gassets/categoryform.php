<div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Category Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="cat_id" value="<?= $category->cat_id ?>" hidden>
                                            <input  type="text" id="currncy-name" name="category_name" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $category->cat_name ?>">
                                        </div>
                                    </div>