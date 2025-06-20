function handleRadioClick(clicked) {
    document.querySelectorAll("input[type=radio]").forEach(radio => {
        if (radio !== clicked) radio.checked = false;
    });
    console.log('clicked')
}

