<template>
  <div class="reports-dashboard">
    <h1>Rapports de Performances</h1>

    <!-- Filtres pour afficher les rapports par agent et date -->
    <div class="filters mb-4">
      <div class="form-group">
        <label for="agentFilter">Filtrer par agent :</label>
        <select v-model="selectedAgent" id="agentFilter" class="form-control">
          <option value="">Tous les agents</option>
          <option v-for="agent in agents" :key="agent.id" :value="agent.name">{{ agent.name }}</option>
        </select>
      </div>

      <div class="form-group">
        <label for="dateFilter">Filtrer par date :</label>
        <input type="date" v-model="selectedDate" id="dateFilter" class="form-control" />
      </div>

      <button @click="generateReport" class="btn btn-primary mt-3">Générer le rapport</button>
    </div>

    <!-- Tableau des rapports -->
    <table class="table table-bordered" v-if="reportData.length > 0">
      <thead>
        <tr>
          <th>Agent</th>
          <th>Tickets Résolus</th>
          <th>Tickets En Cours</th>
          <th>Temps Moyen de Réponse (heures)</th>
          <th>Taux de Satisfaction (%)</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in reportData" :key="report.agent">
          <td>{{ report.agent }}</td>
          <td>{{ report.ticketsResolved }}</td>
          <td>{{ report.ticketsInProgress }}</td>
          <td>{{ report.avgResponseTime }}</td>
          <td>{{ report.satisfactionRate }}</td>
        </tr>
      </tbody>
    </table>
    <p v-else>Aucune donnée disponible pour les filtres appliqués.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      agents: [], // Liste des agents
      selectedAgent: '', // Filtre d'agent
      selectedDate: '', // Filtre de date
      reportData: [], // Données des rapports
    };
  },
  methods: {
    // Récupérer les agents depuis l'API
    async fetchAgents() {
      try {
        const response = await fetch('/api/agents');
        this.agents = await response.json();
      } catch (error) {
        console.error('Erreur lors de la récupération des agents :', error);
      }
    },

    // Générer le rapport en fonction des filtres appliqués
    async generateReport() {
      try {
        // const response = await fetch(`/api/reports?agent=${this.selectedAgent}&date=${this.selectedDate}`);
        const response = await fetch(`/api/tickets/reports`);

        this.reportData = await response.json();
        console.log(this.reportData)
      } catch (error) {
        console.error('Erreur lors de la génération du rapport :', error);
      }
    },
  },
  mounted() {
    this.fetchAgents();
  },
};
</script>

<style scoped>
.reports-dashboard {
  max-width: 1000px;
  margin: 0 auto;
}

.filters {
  display: flex;
  gap: 20px;
}

.table {
  margin-top: 20px;
}

.form-group {
  flex: 1;
}
</style>
