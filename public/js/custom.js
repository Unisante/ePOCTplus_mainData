function comparePatients() {
  let checkedValue = [];
  let inputElements = document.getElementsByClassName('messageCheckbox');
  for(let i=0; inputElements[i]; ++i){
    if(inputElements[i].checked){
      checkedValue.push(inputElements[i].value)
    }
  }
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
  if(checkedValue.length == 2){
    location.href = `/medicalCases/compare/${checkedValue[0]}/${checkedValue[1]}`;
  }else{
    $("#modalCheckBoxMedical").modal('show');
  }
}

function mergePatients(){
  let checkedValue = [];
  let inputElements = document.getElementsByClassName('messageCheckbox');
  for(let i=0; inputElements[i]; ++i){
    if(inputElements[i].checked){
      checkedValue.push(inputElements[i].value)
    }
  }
  if(checkedValue.length == 2){
    location.href = `/patients/merge/${checkedValue[0]}/${checkedValue[1]}`;
  }else{
    $("#modalCheckBoxMerge").modal('show');
  }
}

function takeId(id){
  $("#setId1").html(id);
  $("#patient_id:text").val(id);
}
function takeCaseId(id){
    $("#setId1").html(id);
    $("#medicalcase_id:text").val(id);
}

// $('.datepicker').datepicker({
//   weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
//   showMonthsShort: true
//   })
