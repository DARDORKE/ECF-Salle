document.getElementById("modulesDropdownLink").addEventListener('click', (event) => {
    event.preventDefault();
    console.log('go');
    document.getElementById("modules-card").classList.add('d-block');
    document.getElementById("modules-card").classList.remove('d-none');
    document.getElementById("structure-card").classList.add('d-none');
    document.getElementById("structure-card").classList.remove('d-block');
    document.getElementById("module-link").classList.add('active');
    document.getElementById("structures-link").classList.remove('active');
})

document.getElementById("modulesCardLink").addEventListener('click', (event) => {
    event.preventDefault();
    console.log('go');
    document.getElementById("modules-card").classList.add('d-block');
    document.getElementById("modules-card").classList.remove('d-none');
    document.getElementById("structure-card").classList.add('d-none');
    document.getElementById("structure-card").classList.remove('d-block');
    document.getElementById("module-link").classList.add('active');
    document.getElementById("structures-link").classList.remove('active');
})


document.getElementById("structuresDropdownlink").addEventListener('click', (event) => {
    event.preventDefault();
    console.log('go');
    document.getElementById("modules-card").classList.add('d-none');
    document.getElementById("modules-card").classList.remove('d-block');
    document.getElementById("structure-card").classList.add('d-block');
    document.getElementById("structure-card").classList.remove('d-none');
    document.getElementById("module-link").classList.remove('active');
    document.getElementById("structures-link").classList.add('active');
})

document.getElementById("structuresCardlink").addEventListener('click', (event) => {
    event.preventDefault();
    console.log('go');
    document.getElementById("modules-card").classList.add('d-none');
    document.getElementById("modules-card").classList.remove('d-block');
    document.getElementById("structure-card").classList.add('d-block');
    document.getElementById("structure-card").classList.remove('d-none');
    document.getElementById("module-link").classList.remove('active');
    document.getElementById("structures-link").classList.add('active');
})