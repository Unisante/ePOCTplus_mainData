@extends('adminlte::page')

{{-- <link href="{{ asset('css/datatable.css') }}" rel="stylesheet"> --}}

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span>
            <h3>Health Facilities</h3>
          </span>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @if (session('error'))
            <div class="alert alert-danger">
              <span>{{session('error')}}</span>
            </div>
          @endif
          {{-- @include('layouts.datatable') --}}
          <div class="row">
            <div class="col-md-10 offset-md-1">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Country</th>
                      <th scope="col">Area</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse( $healthFacilities as $facility)
                      <tr>
                        <td>{{$facility->name}}</td>
                        <td>{{$facility->country}}</td>
                        <td>{{$facility->area}}</td>
                        <td>
                          <div class="btn-group btn-group-sm">
                            <button onClick="viewFacility({{$facility}})" class="btn btn-outline-primary">View</button>
                            <button
                            data-name="{{$facility->name}}"
                            data-country="{{$facility->country}}"
                            data-area="{{$facility->area}}"
                            data-long="{{$facility->long}}"
                            data-lat="{{$facility->lat}}"
                            data-hf_mode="{{$facility->hf_mode}}"
                            data-local_data_ip="{{$facility->local_data_ip}}"
                            data-pin_code="{{$facility->pin_code}}"
                            data-route="{{route('health-facilities.update', ['health_facility'=>$facility])}}"
                            class="btn btn-outline-warning editDevice">Edit
                          </button>
                          <button
                            data-delete_route="{{route('health-facilities.destroy', ['health_facility'=>$facility])}}"
                            class="btn btn-outline-danger destroy">Delete
                          </button>
                          <button
                            data-devices_route="{{route('health-facilities.manage-device', ['health_facility'=>$facility])}}"
                            data-facility="{{$facility->id}}"
                            class="btn btn-outline-success manageDevice">Devices
                          </button>
                        </div>
                      </tr>
                    @empty
                      <p>No facilities</p>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('healthFacilities.modals');

<script>
  const viewFacility = function (facility) {
    $("#facility_name").text(facility.name);
    $("#facility_country").text(facility.country);
    $("#facility_area").text(facility.area);
    $("#facility_long").text(facility.long);
    $("#facility_lat").text(facility.lat);
    $("#facility_hf_mode").text(facility.hf_mode);
    $("#facility_local_data_ip").text(facility.local_data_ip);
    $("#facility_pin_code").text(facility.pin_code);
    $('#showFacility').modal('show');
  };

  const viewDevice = function (device) {
    $("#device_name_title").text(device.name);
    $("#device_name").text(device.name);
    $("#device_type_label").text(device.type_label);
    $("#device_model").text(device.model);
    $("#device_brand").text(device.brand);
    $("#device_os").text(device.os);
    $("#device_os_version").text(device.os_version);
    $("#device_health_facility_name").text(device.health_facility_name);
    $("#device_oauth_client_id").text(device.oauth_client_id);
    $("#device_mac_address").text(device.mac_address);
    $("#device_redirect").text(device.redirect);
    $("#device_last_seen").text(device.last_seen);
    $('#showDevice').modal('show');
  };

  const editDevice = function (data) {
    $("#edit_health_facility_form").prop('action', data.route);
    $("#edit_name").val(data.name);
    $("#edit_country").val(data.country);
    $("#edit_area").val(data.area);
    $("#edit_long").val(data.long);
    $("#edit_lat").val(data.lat);
    $("#edit_hf_mode").val(data.hf_mode);
    $("#edit_local_data_ip").val(data.local_data_ip);
    $("#edit_pin_code").val(data.pin_code);
    $('#editHealthFacilty').modal('show');
  };

  $("#deleteFacility").on("hidden.bs.modal", function(){
    clearModal();
  });

  $("#editHealthFacilty").on("hidden.bs.modal", function(){
    clearModal();
  });

  $("#manage_device_modal").on("hidden.bs.modal", function(){
    clearModal();
  });

  $(".editDevice").click(function () {
    editDevice($(this).data());
  });

  $(".manageDevice").click(function () {
    manageDevice($(this).data());
  });

  $(".destroy").click(function () {
    $("#delete_health_facility_form").prop('action', $(this).data().delete_route);
    $('#deleteFacility').modal('show');
  });

  $("#assign_device").submit(function(event) {
    var device_id = $('#selectDevices').val();
    if (!device_id) {
      return false;
    }
    var facility_id = $('#selectDevices').data('facility');
    assignDevice(facility_id, device_id);
    event.preventDefault();
  });

  const clearModal = function () {
    $(".modal-backdrop").remove();
    $(".alert-danger").remove();
    $("#manage_device_modal").removeData();
    $('#selectDevices option').not(':first').remove();
    $('#devices_table tbody').empty();
  }

  const manageDevice = function (data) {
    $.ajax(
      {
        url: data.devices_route,
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function(xhr, type) {
          if (!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
          }
        },
        success: function (response) {
          devices_props = ["name", "type", "oauth_client_id", "health_facility_name", "last_seen", "actions"];
          $('#selectDevices').attr("data-facility", JSON.stringify(data.facility));
          $.each(response.unassignedDevices, function(i, unassignedDevice) {
            $('#selectDevices').append(
              $('<option>')
              .val(unassignedDevice.id)
              .html(unassignedDevice.name)
            );
            });
          if (response.devices.length > 0) {
            $.each(response.devices, function(i, device) {
              var tr = $('<tr>');
              var actionsButtons = "<div class='btn-group btn-group-sm'><button type='button' onClick='viewDevice("+JSON.stringify(device)+")' class='btn btn-outline-primary'>View</button>";
              actionsButtons += `<button type='button' onClick='unassignDevice(${device.health_facility_id},${device.id})' id=device_${device.id} class='btn btn-outline-danger'>Unassign</button></div>`;
              device.actions = actionsButtons;
              $.each(devices_props, function(i, prop) {
                $('<td>').html(device[prop]).appendTo(tr);
              });
              $('#devices_table').append(tr);
            });
          } else {
            var noDeviceFound = "<td align='center' colspan='6'>No device found for this health facility</td>";
            $('#devices_table tbody').append($('<tr>').val(noDeviceFound).html(noDeviceFound));
          }
          $('#manage_device_modal').modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $(".alert-danger").remove();
          $('.content').before(
            $('<div/>')
              .addClass("alert alert-danger")
              .append("<span/>")
                .text(`Error : ${errorThrown}`)
          );
        }
      }
    );
  }

  const unassignDevice = function (health_facility_id, device_id) {
    $.ajax(
      {
        url: `health-facilities/${health_facility_id}/unassign-device/${device_id}`,
        type: 'POST',
        dataType: 'json',
        beforeSend: function(xhr, type) {
          if (!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
          }
        },
        success: function (response){
          clearModal();
          var data = {};
          data.devices_route = `health-facilities/${health_facility_id}/manage-devices`;
          data.facility = health_facility_id;
          manageDevice(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $(".alert-danger").remove();
          $('#devices_table').before(
            $('<div/>')
              .addClass("alert alert-danger alert-dismissible fade show")
              .append("<span/>")
                .text(`Error : ${errorThrown}`)
          );
        }
      }
    );
  }

  const assignDevice = function (health_facility_id, device_id) {
    $.ajax(
      {
        url: `health-facilities/${health_facility_id}/assign-device/${device_id}`,
        type: 'POST',
        dataType: 'json',
        beforeSend: function(xhr, type) {
          if (!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
          }
        },
        success: function (response){
          clearModal();
          var data = {};
          data.devices_route = `health-facilities/${health_facility_id}/manage-devices`;
          data.facility = health_facility_id;
          manageDevice(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $(".alert-danger").remove();
          $('.modal-body').before(
            $('<div/>')
              .addClass("alert alert-danger alert-dismissible fade show")
              .append("<span/>")
                .text(`Error : ${errorThrown}`)
          );
        }
      }
    );
  }

</script>

@stop
