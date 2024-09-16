<template>
  <div class="ticket-system container mt-5">
    <!-- Ticket Creation Form -->
    <h2 class="mb-4">Créer un Ticket</h2>
    <form @submit.prevent="createTicket">
      <div class="mb-3">
        <label for="title" class="form-label">Titre:</label>
        <input
          v-model="newTicket.title"
          id="title"
          type="text"
          class="form-control"
          placeholder="Entrez le titre du ticket"
          required
        />
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description du problème:</label>
        <textarea
          v-model="newTicket.description"
          id="description"
          class="form-control"
          rows="3"
          placeholder="Décrivez le problème"
          required
        ></textarea>
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Statut:</label>
        <select v-model="newTicket.status" id="status" class="form-select">
          <option value="en cours">En cours</option>
          <option value="résolu">Résolu</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Créer le Ticket</button>
    </form>

    <!-- Ticket List -->
    <h2 class="mt-5">Liste des Tickets</h2>
    <div v-if="tickets.length > 0" class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Statut</th>
            <th>Nouvelle mise à jour</th>
            <th>Commentaires</th>
            <th>Ajouter un Commentaire</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in tickets" :key="ticket.id">
            <td>{{ ticket.title }}</td>
            <td>{{ ticket.description }}</td>
            <td>{{ ticket.status }}</td>
            <td>
              <select v-model="ticket.newStatus" class="form-select">
                <option value="en cours">En cours</option>
                <option value="résolu">Résolu</option>
              </select>
            </td>
            <td>
              <ul>
                <li v-for="comment in ticket.comments" :key="comment">{{ comment }}</li>
              </ul>
            </td>
            <td>
              <textarea
                v-model="ticket.newComment"
                class="form-control"
                placeholder="Ajouter un commentaire"
              ></textarea>
              <button @click="addComment(ticket)" class="btn btn-secondary mt-2">
                Ajouter un commentaire
              </button>
            </td>
            <td>
              <button @click="updateTicket(ticket)" class="btn btn-success">
                Mettre à jour
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p v-else class="text-muted">Aucun ticket trouvé.</p>
  </div>
</template>

<script>
export default {
  name: 'tickets',
  data() {
    return {
      newTicket: {
        title: '',
        description: '',
        status: 'en cours',
        call_id: 1,
        agent_id: 1
      },
      tickets: [],
    };
  },
  methods: {
    async createTicket() {console.log(this.newTicket)
      try {
        const response = await fetch('/api/tickets', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.newTicket),
        });
        const data = await response.json();
        data.comments = [];
        this.tickets.push(data);
        this.newTicket = { title: '', description: '', status: 'en cours' };
      } catch (error) {
        console.error('Erreur lors de la création du ticket:', error);
      }
    },

    async updateTicket(ticket) {
      try {
        const response = await fetch(`/api/tickets/${ticket.id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ status: ticket.newStatus }),
        });
        const data = await response.json();
        ticket.status = data.status;
      } catch (error) {
        console.error('Erreur lors de la mise à jour du ticket:', error);
      }
    },

    async addComment(ticket) {
      try {
        const response = await fetch(`/api/tickets/${ticket.id}/comment`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ comment: ticket.newComment }),
        });
        ticket.comments.push(ticket.newComment);
        ticket.newComment = '';
      } catch (error) {
        console.error("Erreur lors de l'ajout du commentaire:", error);
      }
    },
  },

  async mounted() {
    try {
      const response = await fetch('/api/tickets');
      const data = await response.json();
      this.tickets = data.map(ticket => ({
        ...ticket,
        comments: ticket.comments || [],
      }));
    } catch (error) {
      console.error('Erreur lors de la récupération des tickets:', error);
    }
  },
};
</script>

<style scoped>
.ticket-system {
  max-width: 1000px;
  margin: 0 auto;
}

textarea {
  resize: none;
}
</style>
