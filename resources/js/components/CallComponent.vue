<template>
    <div class="container mt-5">
        <h2 class="mb-4">Enregistrer un Appel</h2>
        <form @submit.prevent="addCall" class="border p-4 rounded">
            <div class="form-group mb-3">
                <label for="subject" class="form-label">Sujet :</label>
                <input v-model="call.subject" id="subject" type="text" class="form-control" required />
            </div>

            <div class="form-group mb-3">
                <label for="duration" class="form-label">Durée (minutes) :</label>
                <input v-model="call.duration" id="duration" type="number" class="form-control" required />
            </div>

            <div class="form-group mb-3">
                <label for="call_time" class="form-label">Heure de l'appel :</label>
                <input v-model="call.call_time" id="call_time" type="datetime-local" class="form-control" required />
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'Home',
    data() {
        return {
            call: { 
                agent_id: 1,
                duration: 0,
                subject: '',
                call_time:'',              
            },
        };
    },
    methods: {
        async addCall() {
            try {
                console.log(this.call)
                const response = await axios.post('/api/calls', this.call);
                console.log(response.data);
                alert('Appel enregistré avec succès.');
                // Réinitialiser le formulaire après l'enregistrement
                this.call = { subject: '', duration: 0, call_time: '', agent_id: 4 };
            } catch (error) {
                console.error('Erreur lors de l\'enregistrement de l\'appel:', error);
            }
        },
    }
}
</script>

<style scoped>
.container {
    max-width: 600px;
}

button {
    width: 100%;
}

.form-group {
    margin-bottom: 1rem;
}

h2 {
    text-align: center;
}

.border {
    border: 1px solid #dee2e6;
}
</style>
