let names = []; 
let pickedIndex = -1; 

function addName() {
    let input = document.getElementById("nameInput");
    let name = input.value.trim();
    if (name !== "") {
        names.push(name);
        input.value = "";
    }
}

function startPicking() {
    let pickedDialog = document.getElementById("pickedDialog");
    let pickedDialogContent = document.getElementById("pickedDialogContent");
    let button = document.querySelector('.button2');

    if (names.length > 0) {
        
        if (button.textContent === "Start") {
            pickedIndex = Math.floor(Math.random() * names.length);
            pickedDialogContent.textContent = names[pickedIndex];
            pickedDialog.showModal();
            button.textContent = "Pick Again";
        } else {
            pickedIndex = -1;
            pickedDialogContent.textContent = "";
            pickedDialog.close();
            button.textContent = "Start";
        }
    } else {
        pickedDialogContent.textContent = "Please add names first.";
    }
}

function closeDialog() {
    let pickedDialog = document.getElementById("pickedDialog");
    pickedDialog.close();
}




function pickName() {
    const pickedNameElement = document.getElementById('pickedName');
    const randomIndex = Math.floor(Math.random() * names.length);
    const pickedName = names[randomIndex];
    
    pickedNameElement.textContent = `Picked name: ${pickedName}`;
}
