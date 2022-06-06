const container = document.querySelector('.container');
const seats = document.querySelectorAll('.row .seat:not(.occupied)');
const count = document.getElementById('count');
const price = document.getElementById('price');

attachFormSubmitEvent("form");

function attachFormSubmitEvent(formId){
  document.getElementById(formId).addEventListener("submit", formSubmit);
}

function formSubmit(){
  const selectedSeats = JSON.parse(localStorage.getItem('selectedSeats'));
  var redirect = "reservation_process.php"
  redirect = redirect.concat("?count=",count.innerText);
  if (document.getElementById('email')){
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (!re.test(String(document.getElementById('email').value).toLowerCase())){
        document.getElementById('email').style.border = "2px solid #f00";
        return;
      } else {
        redirect = redirect.concat("&email=",document.getElementById('email').value);
      }
  }

  seats.forEach((seat, index) => {
    if (selectedSeats.indexOf(index) > -1) {
      redirect = redirect.concat("&seats[]=",seat.innerText);
    }
  });
  redirect += "&price="+price.innerText;
  location.href = redirect;
}

const populateUI = () => {
  const selectedSeats = JSON.parse(localStorage.getItem('selectedSeats'));

  if (selectedSeats !== null && selectedSeats.length > 0) {
    seats.forEach((seat, index) => {
      if (selectedSeats.indexOf(index) > -1) {
        seat.classList.add('selected');
      }
    });
  }

  count.innerText = selectedSeats.length;

};

console.log(document.referrer);
if(document.referrer == "login.php"){
  populateUI();
}

const updateSelectedSeatsCount = () => {
  const selectedSeats = document.querySelectorAll('.row .selected');

  const seatsIndex = [...selectedSeats].map(seat => [...seats].indexOf(seat));

  localStorage.setItem('selectedSeats', JSON.stringify(seatsIndex));

  const selectedSeatsCount = selectedSeats.length;

  count.innerText = selectedSeatsCount;
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
    
    if(target.contains("pc1")){
      if(target.contains("selected")){
        price.innerText = Number(price.innerText)+Number(250);
      } else {
        price.innerText = Number(price.innerText)-Number(250);
      }
    } else if (target.contains("pc2")){
      if(target.contains("selected")){
        price.innerText = Number(price.innerText)+Number(200);
      } else {
        price.innerText = Number(price.innerText)-Number(200);
      }
    } else if (target.contains("pc3")){
      if(target.contains("selected")){
        price.innerText = Number(price.innerText)+Number(150);
      } else {
        price.innerText = Number(price.innerText)-Number(150);
      }
    }
    
    updateSelectedSeatsCount();
  }
});
