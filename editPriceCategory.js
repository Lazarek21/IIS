const container = document.getElementsByClassName('bus')[0];
const seats = document.getElementsByClassName('seat');

const populateUI = () => {
    for(seat of seats){
        seat.classList.remove('occupied');
    }
};

populateUI();

const updateSelectedSeatsCount = () => {
  const selectedSeats = document.querySelectorAll('.row .selected');

  const seatsIndex = [...selectedSeats].map(seat => [...seats].indexOf(seat));

  localStorage.setItem('selectedSeats', JSON.stringify(seatsIndex));

  const selectedSeatsCount = selectedSeats.length;
};

// Seat select event
container.addEventListener('click', e => {
  if(!e.target.classList.contains('occupied') && !e.target.parentNode.classList.contains('occupied')){
    var target;
    if(e.target.classList.contains('seat')){
      target = e.target.classList;
    } else if(e.target.parentNode.classList.contains('seat')){
      target = e.target.parentNode.classList;
    } else {
      return;
    }
    target.toggle('selected');
    updateSelectedSeatsCount();
  }
});

attachFormSubmitEvent("form");

function attachFormSubmitEvent(formId){
  document.getElementById(formId).addEventListener("submit", formSubmit);
}

function formSubmit(){
    var redirect = "processPriceCategory.php?category="+document.getElementById("category").value;
    redirect += "&linkPK="+document.getElementById("link").children[0].innerText;
    for(seat of seats){
        if(seat.classList.contains("selected")){
            redirect += "&cond[]="+seat.innerText;
            seat.classList.remove("selected");
        }
    }
    console.log(redirect);
    location.href = redirect;
}
