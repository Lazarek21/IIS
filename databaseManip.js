const styled_table = document.getElementById('styled-table');
const newButton = document.getElementById('newButton');
const editButton = document.getElementById('editButton');
const deleteButton = document.getElementById('deleteButton');

if(editButton)
    editButton.style.visibility = "hidden";
deleteButton.style.visibility = "hidden";


styled_table.addEventListener('click', e => {
    if(e.target.parentNode.classList.contains("tableTr")){
        var sibling = document.getElementsByClassName('tableTr selected');
        
        if(sibling.length > 0){
            if(sibling[0] != e.target.parentNode){
                console.log(sibling[0]);
                sibling[0].classList.toggle('selected');
            } else {
                if(deleteButton.style.visibility == "hidden"){
                    if(editButton)
                        editButton.style.visibility = "visible";
                    deleteButton.style.visibility = "visible";
                    //console.log(e.target.parentNode.cells[0].innerText);
                    localStorage.setItem('tablePK', e.target.parentNode.cells[0].innerText);
                    //localStorage.setItem('dbRow', );
                } else {
                    if(editButton)
                        editButton.style.visibility = "hidden";
                    deleteButton.style.visibility = "hidden";
                    localStorage.removeItem('tablePK');
                }
            }
        } else {
            //console.log(e.target.parentNode.cells[0].innerText);
            localStorage.setItem('tablePK', e.target.parentNode.cells[0].innerText);
            if(editButton)
                editButton.style.visibility = "visible";
            deleteButton.style.visibility = "visible";
        }

        e.target.parentNode.classList.toggle('selected');
    }
});
