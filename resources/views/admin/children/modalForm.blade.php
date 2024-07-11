<div class="modal fade" id="childModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form method="post" id="childForm" action="{{ route('children.create') }}" name="childForm" class="form-horizontal">
                    <input type="hidden" name="child_id" id="child_id">
                    <div class="form-group">
                        <label for="firstname" class="col-md-12 control-label">First Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" value="" maxlength="50" required="">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="lastname" class="col-md-12 control-label">Last Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" value="" maxlength="50" required="">
                        </div>
                    </div>       
                        
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>   