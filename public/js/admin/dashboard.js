async function onClickDeleteButton(event)
{
    event.preventDefault();

    const confirmation = window.confirm('$etes-vous certain de vouloir supprimer ce produit ?');

    if (confirmation) {

        const url = event.currentTarget.href;

        const options = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
    
        const response = await fetch(url, options);
        const deletedProductId = await response.json();
    
        const row = document.getElementById(`product-${deletedProductId}`);
    
        if (row != null) {
            row.remove();
        }
    }
}


const deleteButtons = document.querySelectorAll('.btn-delete');

for (const deleteButton of deleteButtons) {

    deleteButton.addEventListener('click', onClickDeleteButton);
}