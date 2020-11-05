const select = document.querySelector('select');
const databox = document.querySelector('div');

select.addEventListener('change', setRole);

function setRole() {
  const choice = select.value;

  if (choice === 'patient') {
    databox.innerHTML += '<label for="family_code">Family Code</label> <input type="text" name="family_code">';
    databox.innerHTML += '<label for="emergency_contact">Emergency Contact</label> <input type="text" name="emergency_contact">';
    databox.innerHTML += '<label for="relation_to_contact">Relation to Emergency Contact</label> <input type="text" name="relation_to_contact">';
  } else {
    databox.innerHTML = "";
  }
}
