<?php
require_once 'includes/db.php';
$pageTitle = 'Contact Us - Swastik Art & Craft';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $conn->prepare("
                INSERT INTO contact_messages (name, email, phone, subject, message, created_at) 
                VALUES (:name, :email, :phone, :subject, :message, NOW())
            ");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message
            ]);
            // Redirect with success parameter to prevent resubmission
            header('Location: contact.php?sent=1');
            exit();
        } catch (PDOException $e) {
            $error = 'Sorry, there was an error sending your message. Please try again.';
        }
    }
}

include 'includes/header.php';
?>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.8s ease-out;
}

.animate-slideInLeft {
    animation: slideInLeft 0.8s ease-out;
}

.animate-slideInRight {
    animation: slideInRight 0.8s ease-out;
}

.hero-bg {
    background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.4)), url('../assets/images/bowl2.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.glass-effect {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.input-focus {
    transition: all 0.3s ease;
}

.input-focus:focus {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.2);
}

.btn-hover {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
}

.btn-hover::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-hover:hover::before {
    left: 100%;
}

.contact-card {
    transition: all 0.3s ease;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.floating {
    animation: floating 3s ease-in-out infinite;
}

@keyframes floating {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Tooltip Styles */
.tooltip {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 16px 24px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
    z-index: 1000;
    transform: translateX(400px);
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.tooltip.show {
    transform: translateX(0);
}

.tooltip i {
    font-size: 20px;
}

@keyframes checkmark {
    0% { transform: scale(0) rotate(0deg); }
    50% { transform: scale(1.2) rotate(180deg); }
    100% { transform: scale(1) rotate(360deg); }
}

.tooltip.show i {
    animation: checkmark 0.6s ease-out 0.2s both;
}
</style>

<!-- Hero Header -->
<section class="hero-bg min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-amber-900/20 to-orange-900/20"></div>
    <div class="relative z-10 text-center text-white px-4 animate-fadeInUp">
        <h1 class="text-6xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-amber-200 to-orange-200 bg-clip-text text-transparent">
            Contact Us
        </h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-2xl mx-auto">
            Let's create something beautiful together
        </p>
        <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-orange-400 mx-auto rounded-full"></div>
    </div>
   <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
   <div id="scrollDownBtn" 
     class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce cursor-pointer">
    <i class="fas fa-chevron-down text-3xl"></i>
</div>
</div>
</section>

<!-- Contact Section -->
<section id="contact-section" class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-fadeInUp">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Contact Form -->
            <div class="animate-slideInLeft">
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-paper-plane text-amber-600 mr-3"></i>
                        Send us a Message
                    </h3>
                
                    <?php if ($success): ?>
                        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl animate-fadeInUp">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                                <span class="text-green-700 font-medium"><?= htmlspecialchars($success) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl animate-fadeInUp">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
                                <span class="text-red-700 font-medium"><?= htmlspecialchars($error) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                
                    <form method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-amber-600">
                                    <i class="fas fa-user mr-2"></i>Name *
                                </label>
                                <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl input-focus focus:ring-0 focus:border-amber-500 bg-white/80">
                            </div>
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-amber-600">
                                    <i class="fas fa-envelope mr-2"></i>Email *
                                </label>
                                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl input-focus focus:ring-0 focus:border-amber-500 bg-white/80">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-amber-600">
                                    <i class="fas fa-phone mr-2"></i>Phone
                                </label>
                                <input type="tel" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>"
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl input-focus focus:ring-0 focus:border-amber-500 bg-white/80">
                            </div>
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-amber-600">
                                    <i class="fas fa-tag mr-2"></i>Subject
                                </label>
                                <input type="text" name="subject" value="<?= htmlspecialchars($subject ?? '') ?>"
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl input-focus focus:ring-0 focus:border-amber-500 bg-white/80">
                            </div>
                        </div>
                        
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-amber-600">
                                <i class="fas fa-comment mr-2"></i>Message *
                            </label>
                            <textarea name="message" rows="6" required
                                      class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl input-focus focus:ring-0 focus:border-amber-500 bg-white/80 resize-none"><?= htmlspecialchars($message ?? '') ?></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white px-8 py-4 rounded-xl font-semibold btn-hover">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Contact Info & Map -->
            <div class="space-y-8 animate-slideInRight">
                <!-- Contact Information -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                        <i class="fas fa-map-marked-alt text-amber-600 mr-3"></i>
                        Contact Information
                    </h3>
                    <div class="space-y-6">
                        <div class="contact-card p-4 rounded-xl bg-white/50 border border-white/20">
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-br from-amber-500 to-orange-500 p-4 rounded-full floating">
                                    <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">Address</h4>
                                    <p class="text-gray-600">16 Paknajol Marg, Kathmandu 44600, Nepal</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-card p-4 rounded-xl bg-white/50 border border-white/20">
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-br from-green-500 to-emerald-500 p-4 rounded-full floating" style="animation-delay: 0.5s">
                                    <i class="fas fa-phone text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">Phone</h4>
                                    <p class="text-gray-600">+977 9821589863</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-card p-4 rounded-xl bg-white/50 border border-white/20">
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 p-4 rounded-full floating" style="animation-delay: 1s">
                                    <i class="fas fa-envelope text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">Email</h4>
                                    <p class="text-gray-600">Ushanraz@gmail.com</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-card p-4 rounded-xl bg-white/50 border border-white/20">
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-4 rounded-full floating" style="animation-delay: 1.5s">
                                    <i class="fas fa-clock text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">Business Hours</h4>
                                    <p class="text-gray-600">Mon - Sat: 9:00 AM - 6:00 PM<br>Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Map -->
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-map text-amber-600 mr-3"></i>
                        Find Us
                    </h3>
                    <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden shadow-lg">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.1234567890123!2d85.3086441!3d27.7141801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb196344815c0d%3A0x4530a49e118e10ce!2s16%20Paknajol%20Marg%2C%20Kathmandu%2044600%2C%20Nepal!5e0!3m2!1sen!2snp!4v1234567890123!5m2!1sen!2snp"
                            style="width:100%; border:0;" 
                            height="300" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded-xl">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Add scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all animated elements
document.querySelectorAll('.animate-fadeInUp, .animate-slideInLeft, .animate-slideInRight').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    observer.observe(el);
});

// Form validation with smooth feedback
const form = document.querySelector('form');
const inputs = form.querySelectorAll('input, textarea');

inputs.forEach(input => {
    input.addEventListener('blur', function() {
        if (this.hasAttribute('required') && !this.value.trim()) {
            this.classList.add('border-red-300');
            this.classList.remove('border-gray-200');
        } else {
            this.classList.remove('border-red-300');
            this.classList.add('border-gray-200');
        }
    });
    
    input.addEventListener('input', function() {
        if (this.classList.contains('border-red-300') && this.value.trim()) {
            this.classList.remove('border-red-300');
            this.classList.add('border-gray-200');
        }
    });
});
// Smooth easing scroll for arrow
document.getElementById('scrollDownBtn').addEventListener('click', function () {
    const target = document.getElementById('contact-section').offsetTop;
    const start = window.scrollY;
    const distance = target - start;
    const duration = 1000; // animation duration in ms
    let startTime = null;

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;
        const progress = Math.min(timeElapsed / duration, 1);

        // Ease in-out cubic (smooth acceleration + deceleration)
        const ease = progress < 0.5
            ? 4 * progress * progress * progress
            : 1 - Math.pow(-2 * progress + 2, 3) / 2;

        window.scrollTo(0, start + distance * ease);

        if (timeElapsed < duration) {
            requestAnimationFrame(animation);
        }
    }

    requestAnimationFrame(animation);
});

// Success tooltip function
function showSuccessTooltip() {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.innerHTML = '<i class="fas fa-check-circle"></i><span>Message sent successfully!</span>';
    
    document.body.appendChild(tooltip);
    
    // Show tooltip
    setTimeout(() => {
        tooltip.classList.add('show');
    }, 100);
    
    // Hide tooltip after 4 seconds
    setTimeout(() => {
        tooltip.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(tooltip);
        }, 400);
    }, 4000);
}

// Check if message was sent successfully via URL parameter
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('sent') === '1') {
    showSuccessTooltip();
    // Clean URL without reloading page
    window.history.replaceState({}, document.title, window.location.pathname);
}
</script>
<section class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4 flex items-center">
                    <a href="https://wa.me/9779821589863" target="_blank"
                       class="flex items-center justify-center px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                    <a href="mailto:Ushanraz@gmail.com"
                       class="flex items-center justify-center px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>Email Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>