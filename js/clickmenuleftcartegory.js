// Xử lý click      menu category left
document.addEventListener('DOMContentLoaded', function() {
    const categoryLeftItems = document.querySelectorAll('.cartegory-left-li');
    
    categoryLeftItems.forEach(item => {
        const link = item.querySelector('a');
        const subMenu = item.querySelector('ul');
        
        if (subMenu) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                item.classList.toggle('block');
            });
        }
    });
});