
<div class="modal fade" id="showFacility">
  <div role="document" class="modal-dialog modal-dialog-scrollable modal-lg" aria-hidden="true">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <h5>Showing Health Facility&nbsp;</h5>
          <h5 id="facility_name_title"></h5>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Name</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="facility_name"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Country</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="facility_country"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Area</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="facility_area"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Longitude</h5>
                  </div>
                  <div class="col">
                    <!----> <code id="facility_long"></code>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Latitude</h5>
                  </div>
                  <div class="col">
                    <!----> <code id="facility_lat"></code>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Architecture</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="facility_hf_mode"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">medAL-hub IP (only for Client Server)</h5>
                  </div>
                  <div class="col">
                    <!----> <code id="facility_local_data_ip"></code>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Pin Code</h5>
                  </div>
                  <div class="col">
                    <!----> <code id="facility_pin_code"></code>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>


<div class="modal fade" id="editHealthFacilty">
  <div role="document" class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <h5>Editing Health Facility&nbsp;</h5>
          <h5 id="edit_name_title"></h5>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit_health_facility_form" action="" method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="card">
            <div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">
                          Name
                        </h5>
                      </div>
                      <div class="col"><input type="text" class="form-control" id="edit_name" name="name"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">
                          Country
                        </h5>
                      </div>
                      <div class="col"><input type="text" class="form-control" id="edit_country" name="country"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">
                          Area
                        </h5>
                      </div>
                      <div class="col"><input type="text" class="form-control" id="edit_area" name="area"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">
                          Longitude
                        </h5>
                      </div>
                      <div class="col"><input type="text" class="form-control" id="edit_long" name="long"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">
                          Latitude
                        </h5>
                      </div>
                      <div class="col"><input type="text" class="form-control" id="edit_lat" name="lat"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">Architecture</h5>
                      </div>
                      <div class="col"><select wire:ignore="" class="form-control form-select-lg" id="edit_hf_mode" name="hf_mode">
                          <option disabled="disabled" value="">Please select one</option>
                          <option value="client-server">
                            Client Server
                          </option>
                          <option value="standalone">
                            Standalone
                          </option>
                        </select></div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">
                          medAL-hub IP (only for Client Server)
                        </h5>
                      </div>
                      <div class="col"><input type="text" class="form-control" id="edit_local_data_ip" name="local_data_ip"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">
                          Pin Code
                        </h5>
                      </div>
                      <div class="col"><input type="text" class="form-control" id="edit_pin_code" name="pin_code"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteFacility">
  <div role="document" class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <h4>Warning</h4>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this Health Facility?
    </div>
    <form id="delete_health_facility_form" action="" method="post">
      @csrf
      @method('DELETE')
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="reset" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button></div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="manage_device_modal">
  <div role="document" class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <h5>Devices from Health Facility&nbsp;</h5>
          <h5 id="device_facility_name"></h5>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Assign New Device</h5>
            </div>
            <form id="assign_device" action="" method="post">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="card">
                  <div class="container">
                    <div class="row p-2">
                      <div class="col">
                        <h5 class="card-title">Device Name:</h5>
                      </div>
                      <div class="col">
                        <select class="form-control form-select-lg" id="selectDevices">
                          <option value="">Please select one</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Assign</button>
              </div>
            </form>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="card">
                <div class="table-responsive">
                  <table id="devices_table" class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Auth ID</th>
                        <th>Health Facility</th>
                        <th>Last Seen</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="showDevice">
  <div role="document" class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4  class="modal-title">
          <h5>Showing Device&nbsp;</h5>
          <h5 id="device_name_title"></h5>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Name</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_name"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Type</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_type_label"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Model</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_model"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Brand</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_brand"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Operating System</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_os"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Operating System Version</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_os_version"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">MAC Address</h5>
                  </div>
                  <div class="col">
                    <code id="device_mac_address"></code>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Health Facility</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_health_facility_name"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Authentication ID</h5>
                  </div>
                  <div class="col">
                    <p class="card-text" id="device_oauth_client_id"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="container">
                <div class="row p-3">
                  <div class="col">
                    <h5 class="card-title">Redirect URL</h5>
                  </div>
                  <div class="col">
                    <code id="device_redirect"></code>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
