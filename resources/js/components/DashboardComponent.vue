<template>
  <div class="ticket-dashboard container mt-5">
    <h1 class="mb-4 text-center">Gestion des Tickets</h1>

    <!-- Filtres pour afficher les tickets par agent ou statut -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="agentFilter" class="form-label">Filtrer par agent :</label>
        <select v-model="selectedAgent" id="agentFilter" class="form-select">
          <option value="">Tous les agents</option>
          <option v-for="agent in agents" :key="agent.id" :value="agent.name">{{ agent.name }}</option>
        </select>
      </div>
      <div class="col-md-6">
        <label for="statusFilter" class="form-label">Filtrer par statut :</label>
        <select v-model="selectedStatus" id="statusFilter" class="form-select">
          <option value="">Tous les statuts</option>
          <option value="en cours">En cours</option>
          <option value="résolu">Résolu</option>
          <option value="fermé">Fermé</option>
        </select>
      </div>
    </div>

    <!-- Tableau des tickets -->
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Ticket ID</th>
          <th>Agent</th>
          <th>Date de Création</th>
          <th>Sujet</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="ticket in filteredTickets" :key="ticket.id">
          <td>{{ ticket.id }}</td>
          <td>{{ ticket.agent }}</td>
          <td>{{ formatDate(ticket.created_at) }}</td>
          <td>{{ ticket.subject }}</td>
          <td>{{ ticket.status }}</td>
          <td>
            <select v-model="ticket.status" @change="updateStatus(ticket)" class="form-select">
              <option value="en cours">En cours</option>
              <option value="résolu">Résolu</option>
              <option value="fermé">Fermé</option>
            </select>
            <button class="btn btn-primary btn-sm mt-2" @click="addComment(ticket)">
              Ajouter un Commentaire
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal pour ajouter un commentaire -->
    <div v-if="selectedTicket" class="modal fade show d-block" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ajouter un commentaire pour le ticket {{ selectedTicket.id }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <textarea v-model="newComment" class="form-control" placeholder="Entrez votre commentaire"></textarea>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" @click="submitComment">Envoyer</button>
            <button class="btn btn-secondary" @click="closeModal">Fermer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tickets: [], // Liste des tickets
      agents: [], // Liste des agents
      selectedAgent: '', // Filtre d'agent
      selectedStatus: '', // Filtre de statut
      selectedTicket: null, // Ticket sélectionné pour ajouter un commentaire
      newComment: '', // Nouveau commentaire
    };
  },
  computed: {
    // Filtrer les tickets en fonction des filtres d'agent et de statut
    filteredTickets() {
      return this.tickets.filter(ticket => {
        return (
          (this.selectedAgent === '' || ticket.agent === this.selectedAgent) &&
          (this.selectedStatus === '' || ticket.status === this.selectedStatus)
        );
      });
    },
  },
  methods: {
    formatDate(timestamp) {
      const date = new Date(timestamp);
      return date.toLocaleString();
    },
    async fetchTickets() {
      try {
        const response = await fetch('/api/tickets');
        this.tickets = await response.json();
      } catch (error) {
        console.error('Erreur lors de la récupération des tickets :', error);
      }
    },
    async fetchAgents() {
      try {
        const response = await fetch('/api/agents');
        this.agents = await response.json();
      } catch (error) {
        console.error('Erreur lors de la récupération des agents :', error);
      }
    },
    async updateStatus(ticket) {
      try {
        const response = await fetch(`/api/tickets/${ticket.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ status: ticket.status }),
        });
        if (!response.ok) {
          throw new Error('Erreur lors de la mise à jour du statut');
        }
        alert('Statut mis à jour avec succès');
      } catch (error) {
        console.error('Erreur lors de la mise à jour du statut :', error);
      }
    },
    addComment(ticket) {
      this.selectedTicket = ticket;
      this.newComment = '';
    },
    async submitComment() {
      if (!this.newComment) return;
      try {
        const response = await fetch(`/api/tickets/${this.selectedTicket.id}/comments`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ comment: this.newComment }),
        });
        if (!response.ok) {
          throw new Error('Erreur lors de l\'ajout du commentaire');
        }
        alert('Commentaire ajouté avec succès');
        this.closeModal();
      } catch (error) {
        console.error('Erreur lors de l\'ajout du commentaire :', error);
      }
    },
    closeModal() {
      this.selectedTicket = null;
    },
  },
  mounted() {
    this.fetchTickets();
    this.fetchAgents();
  },
};
</script>

<style scoped>
.modal.show {
  display: block;
}
</style>
