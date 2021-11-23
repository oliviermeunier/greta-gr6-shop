export class Signup {
    
    constructor(){
        this.reloadForm = document.querySelector('.avatar-reload-form');
        this.reloadForm.addEventListener('submit', this.onSubmitReloadForm.bind(this));
    }

    async onSubmitReloadForm(event) {

        // On stoppe la soumission du formulaire
        event.preventDefault();
        
        // On récupère l'URL à interroger dans l'attribut action du formulaire
        const url = this.reloadForm.action;

        // On souhaite envoyer une requête en POST
        const options = {
            method: 'POST'
        };

        // Envoi de la requête AJAX et récupération de la réponse
        const response = await fetch(url, options);
        const svg = await response.text();

        // Mise à jour de l'avatar affiché et du champ caché
        document.querySelector('.avatar-container').innerHTML = svg;
        document.querySelector('[name="svg"]').value = svg;
    }
}