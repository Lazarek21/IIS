const styled_table_stop = document.getElementById('styled-table-stop');
const styled_table_change_stop = document.getElementById('styled-table-change-stop');
const styled_table_reservation = document.getElementById('styled-table-reservation');
const styled_table_change = document.getElementById('styled-table-change');
const styled_table_reservation_done = document.getElementById("styled-table-reservation-done");

const approveButton = document.getElementById('approveButton');
const deleteButton = document.getElementById('deleteButton');

approveButton.style.visibility = "hidden";
deleteButton.style.visibility = "hidden";

if (styled_table_stop != null)
{
    styled_table_stop.addEventListener('click', e => {
        selectTable(e);
        localStorage.setItem('db', 'stop');
    });
}
if (styled_table_change != null)
{
    styled_table_change.addEventListener('click', e => {
        selectTable(e);
        localStorage.setItem('db', 'change_stop');
    });
}

if (styled_table_reservation != null)
{
    styled_table_reservation.addEventListener('click', e => {
        selectTable(e);
        localStorage.setItem('db', 'reservation');
    });
}

if (styled_table_reservation_done != null){
    styled_table_reservation_done.addEventListener('click', e => {
        selectTable(e);
        approveButton.style.visibility = "hidden";
        localStorage.setItem('db', 'reservation');
    });
}


function selectTable(e){
    //console.log(e.target);
    if(e.target.parentNode.classList.contains("tableTr")){
        var sibling = document.getElementsByClassName('tableTr selected');
        
        if(sibling.length > 0){
            if(sibling[0] != e.target.parentNode){
                sibling[0].classList.toggle('selected');
                approveButton.style.visibility = "visible";
            } else {
                if(deleteButton.style.visibility == "hidden"){
                    approveButton.style.visibility = "visible";
                    deleteButton.style.visibility = "visible";
                    localStorage.setItem('tablePK', e.target.parentNode.cells[0].innerText);
                } else {
                    deleteButton.style.visibility = "hidden";
                    approveButton.style.visibility = "hidden";
                    localStorage.removeItem('tablePK');
                }
            }
        } else {
            localStorage.setItem('tablePK', e.target.parentNode.cells[0].innerText);
            deleteButton.style.visibility = "visible";
            approveButton.style.visibility = "visible";
        }

        e.target.parentNode.classList.toggle('selected');
    }
}
