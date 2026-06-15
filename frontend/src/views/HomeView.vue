<script setup lang="ts">
import HomeNavbar from '@/components/home/HomeNavbar.vue'
import HeroSection from '@/components/home/HeroSection.vue'
import EmergencyBanner from '@/components/home/EmergencyBanner.vue'
import HowItWorks from '@/components/home/HowItWorks.vue'
import BloodAvailability from '@/components/home/BloodAvailability.vue'
import WhyDonate from '@/components/home/WhyDonate.vue'
import SuccessStories from '@/components/home/SuccessStories.vue'
import LiveStats from '@/components/home/LiveStats.vue'
import PartnerLogos from '@/components/home/PartnerLogos.vue'
import CallToAction from '@/components/home/CallToAction.vue'
import HomeFooter from '@/components/home/HomeFooter.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <HomeNavbar />
    <HeroSection />
    <EmergencyBanner />
    <HowItWorks />
    <BloodAvailability />
    <WhyDonate />
    <SuccessStories />
    <LiveStats />
    <PartnerLogos />
    <CallToAction />
    <HomeFooter />
  </div>
</template>