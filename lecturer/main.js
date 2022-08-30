const drop_down = document.querySelector('.arrow');
const drop_down_content = document.querySelector('.drop_down');

drop_down.addEventListener('click', () => {
    // console.log('test');
    drop_down_content.classList.toggle('show');
})


// filter names by search
document.querySelectorAll('.search_classroom').forEach(search => {
    search.addEventListener('input', () => {
        // const input = document.querySelector('.search_filter')
        // change input value to lowercase
        const filter = search.value.toLowerCase();
        // console.log(filter);
    
        // Target all items|names
        const filterItems = document.querySelectorAll('.classroom_name');
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


// const check = document.querySelector('.checkbox_');


// check.addEventListener('click', () => {
//     submit.click();
// })

// const submit = document.querySelector('submit');
// submit.addEventListener("submit", () => {
//     console.log('submitted');
// })


