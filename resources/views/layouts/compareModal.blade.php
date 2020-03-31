<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" defer></script>

<div class="modal" tabindex="-1" id="modalCheckBox" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="display">Compare between Two Patients</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" id="modalCheckBoxMedical" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="display">Compare between Two Medical Cases</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  function comparePatients() {
  let checkedValue = [];
  let inputElements = document.getElementsByClassName('messageCheckbox');
  for(let i=0; inputElements[i]; ++i){
        if(inputElements[i].checked){
            checkedValue.push(inputElements[i].value)
        }
  }
  console.log(checkedValue.length)
  if(checkedValue.length == 2){
    location.href = `/patients/compare/${checkedValue[0]}/${checkedValue[1]}`;
  }else{
    $("#modalCheckBox").modal('show');
  }
}
function compareMedicalCases() {
  let checkedValue = [];
  let inputElements = document.getElementsByClassName('messageCheckbox');
  for(let i=0; inputElements[i]; ++i){
        if(inputElements[i].checked){
            checkedValue.push(inputElements[i].value)
        }
  }
  console.log(checkedValue.length)
  if(checkedValue.length == 2){
    location.href = `/medicalCases/compare/${checkedValue[0]}/${checkedValue[1]}`;
  }else{
    $("#modalCheckBoxMedical").modal('show');
  }
}

</script>

<script>
  $(document).ready( function () {
    $('.table').DataTable();
  } )
</script>
