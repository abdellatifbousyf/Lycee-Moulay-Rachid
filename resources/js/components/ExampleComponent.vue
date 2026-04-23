{{-- ✅ المسار الصحيح: resources/js/components/ExampleComponent.vue --}}
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ title }}</div>
                    <div class="card-body">
                        {{ message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// ✅ بيانات متفاعلة (Reactive)
const title = ref('Example Component');
const message = ref('I\'m an example component running on Vue 3 & Laravel 11/12+.');

// ✅ Lifecycle Hook
onMounted(() => {
    console.log('✅ ExampleComponent mounted successfully.');
});
</script>
