<template>
  <div class="home">
    <div class="hero">
      <h1>Blood Donation System</h1>
      <p>Save lives by donating blood or requesting blood for those in need.</p>
      <div class="cta-buttons">
        <router-link to="/register" class="btn btn-primary">Register as Donor</router-link>
        <router-link to="/login" class="btn btn-secondary">Login</router-link>
      </div>
    </div>
    <div class="features">
      <div class="feature-card">
        <h3>Donate Blood</h3>
        <p>Register as a donor and help save lives by donating blood regularly.</p>
      </div>
      <div class="feature-card">
        <h3>Request Blood</h3>
        <p>Submit blood requests for patients in need at your hospital.</p>
      </div>
      <div class="feature-card">
        <h3>Track History</h3>
        <p>View your donation history and request status online.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.home {
  text-align: center;
}

.hero {
  background: white;
  padding: 3rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}

.hero h1 {
  color: #dc2626;
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.hero p {
  color: #666;
  font-size: 1.2rem;
  margin-bottom: 2rem;
}

.cta-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
}

.btn-primary {
  background: #dc2626;
  color: white;
}

.btn-secondary {
  background: #4b5563;
  color: white;
}

.features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.feature-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.feature-card h3 {
  color: #dc2626;
  margin-bottom: 0.5rem;
}

.feature-card p {
  color: #666;
}
</style>
