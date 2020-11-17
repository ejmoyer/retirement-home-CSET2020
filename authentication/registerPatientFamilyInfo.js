const select = document.querySelector('select'); // selects the select tag
const databox = document.querySelector('div'); // selects the empty div tag

select.addEventListener('change', setRole); // when the select is changed

function setRole() { // function for addEventListener
  const choice = select.value; // get the choice made by select

  if (choice == 5) { // If that choice is 5 to show extra data needed for patient
    databox.innerHTML += '<label for="family_code">Family Code</label> <input type="text" name="family_code">';
    databox.innerHTML += '<label for="emergency_contact">Emergency Contact</label> <input type="text" name="emergency_contact">';
    databox.innerHTML += '<label for="relation_to_contact">Relation to Emergency Contact</label> <input type="text" name="relation_to_contact">';
  } else { // otherwise, remove the extra data placed by patient
    databox.innerHTML = "";
  }
}
