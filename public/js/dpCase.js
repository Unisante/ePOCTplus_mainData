$(document).ready(function () {
    console.log("ready!");
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        type: "GET",
        url: "/medicalcases/duplicate2",
    }).done(function (result) {
        let cases = result.mcs;
        // console.log(typeof(cases))
        result.mcs.forEach(dpGroupParent);
    });

    function dpGroupParent(children, index) {
        console.log(index);
        // console.log(group)
        // markup = "<tr><td> + information + </td></tr>"
        markup = `<tr class='table-secondary'><td>For The ${
            index + 1
        }'s Duplicate<td></tr>`;
        tableBody = $("table tbody");
        tableBody.append(markup);
        children.forEach(dpGroupChild);
    }
    function dpGroupChild(child, index) {
        // console.log(child.facility)
        markup = `
        <tr>
        <th scope="row">${index + 1}</th>
        <td>${child.local_medical_case_id}</td>
        <td>${child.patient.local_patient_id}</td>
        <td>${child.consultation_date}</td>
        <td>${child.patient.facility.name}</td>
        <td><input type="checkbox" class="messageCheckbox" value="${
            child.id
        }"></td>
        <td><a  class="btn btn-outline-primary" data-toggle="modal" data-target="#markRow" onclick="takeCaseId(${
            child.id
        })">Mark Duplicate</a></td>
        </tr>`;
        tableBody = $("table tbody");
        tableBody.append(markup);
    }
});
