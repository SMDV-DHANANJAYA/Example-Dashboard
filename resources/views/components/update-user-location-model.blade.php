<div class="modal fade" id="user-location-model" tabindex="-1" role="dialog" aria-labelledby="user-location-model" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User assign form - <span id="form-text" class="text-primary"></span></h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">×</span>
                </button>
            </div>
            <form id="manage-user-location-form" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label>Date <span class="text-danger">*</span></label>
                        <div class="d-flex justify-content-around mt-2 mb-3">
                            <div class="custom-control custom-checkbox mr-1">
                                <input type="checkbox" class="custom-control-input" id="one-day" name="one_day" onchange="changeWorkType({{ \App\Models\UserLocations::ONETIME }})" checked>
                                <label class="custom-control-label" for="one-day">One Day</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-1">
                                <input type="checkbox" class="custom-control-input" id="custom-day" name="custom_day" onchange="changeWorkType({{ \App\Models\UserLocations::CUSTOMDAYS }})">
                                <label class="custom-control-label" for="custom-day">Custom Days</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="every-day" name="every_day" onchange="changeWorkType({{ \App\Models\UserLocations::EVERYDAY }})">
                                <label class="custom-control-label" for="every-day">Every Day</label>
                            </div>
                        </div>
                        <input type="date" id="date" class="form-control" name="date" required>
                        <select class="form-control" name="dates[]" id="dates" style="display: none" multiple>
                            <option value="Mon">Monday</option>
                            <option value="Tue">Tuesday</option>
                            <option value="Wed">Wednesday</option>
                            <option value="Thu">Thursday</option>
                            <option value="Fri">Friday</option>
                            <option value="Sat">Saturday</option>
                            <option value="Sun">Sunday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Start Time <span class="text-danger">*</span></label>
                        <input type="time" id="start-time" class="form-control" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label>End Time <span class="text-danger">*</span></label>
                        <input type="time" id="end-time" class="form-control" name="end_time" required>
                    </div>
                    <div class="form-group">
                        <label>Area (m) <span class="text-danger">*</span></label>
                        <input type="number" id="area" class="form-control" placeholder="Input work area meters" value="50" name="area" required>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="user_id" id="user-id">
                    <input type="hidden" name="location_id" id="location-id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-outline-success">Save</button>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
