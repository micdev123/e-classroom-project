const drop_down = document.querySelector('.dropdown_btn');
const drop_down_content = document.querySelector('.drop_down');

drop_down.addEventListener('click', () => {
    // console.log('test');
    drop_down_content.classList.toggle('show');
})

// Toggle sidebar
// const sidebar = document.querySelector('.sidebar')
// const toggleMenu = document.querySelector('.toggle')

// toggleMenu.addEventListener("click" , () =>{
//     sidebar.classList.toggle("close");
// })


// filter names by search
document.querySelectorAll('.search_filter').forEach(search => {
    search.addEventListener('input', () => {
        // const input = document.querySelector('.search_filter')
        // change input value to lowercase
        const filter = search.value.toLowerCase();
        // console.log(filter);
    
        // Target all items|names
        const filterItems = document.querySelectorAll('.name');
        filterItems.forEach(item => {
            let text = item.textContent;
            // console.log(text);
            if(text.toLowerCase().includes(filter.toLowerCase())) {
                item.parentElement.style.display = '';
            }
            else {
                item.parentElement.style.display = 'none';
            }
            
        })
    
    })
})


// filter role
const options = document.querySelectorAll('.options');
const roles = document.querySelectorAll('.role');

// Looping through :: Filtering
options.forEach((option) => {
    // category.addEventListener('click', filter_work.bind(this, category));
    option.addEventListener('click', () => {
        // console.log(option);
        changeAvtivePosition(option);
        // console.log(category.attributes.id.value);
        roles.forEach((role) => {
            // console.log(optionStatus.textContent.toLowerCase());
            if(role.classList.contains(option.value)) {
                role.parentElement.style.display = 'grid';
                // console.log('yeah');
            }
            else {
                role.parentElement.style.display = 'none';
            //    console.log('nope');
            }
        })
    });
})

function changeAvtivePosition(activeOption) {
   for (const activeOption of options) {
        activeOption.classList.remove('active')
   }
   activeOption.classList.add('active');
}


// dropdown :: students || lecturers
const arrow = document.querySelector('.arrow');
arrow.addEventListener("click", () => {
    // console.log('test');
    let parent = arrow.parentElement.parentElement;
    // console.log(parent);
    parent.classList.toggle("showMenu");
})

// dropdown modules
document.querySelector('.select-field').addEventListener('click',()=>{
    document.querySelector('.modal-list').classList.toggle('show-modules');
    document.querySelector('.select-arrow').classList.toggle('rotate');

});


// 
const select_input = document.querySelector('.input-selector')
const checkboxs = document.querySelectorAll('.checkbox');

let input_modules = [];

for (const checkbox of checkboxs) {
    checkbox.addEventListener('click', (e) => {
        // console.log(e.target);
        let targetModule = e.target;
        let parent = targetModule.parentElement;
        let target_span = parent.querySelector('.span');
        
        if(targetModule.checked === true) {
            console.log(target_span);
            // console.log(target_span.textContent);
            input_modules.push(target_span.textContent);
            select_input.value = input_modules.join(', ');
        }
        else {
            input_modules = input_modules.filter(e => e !== target_span.textContent);
            select_input.value = input_modules.join(', ');
        }
    })
}







