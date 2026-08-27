import { createRoot } from 'react-dom/client';
import TestimonialsCarousel from './react/TestimonialsCarousel.jsx';

document.querySelectorAll('[data-react-root="testimonials"]').forEach((el) => {
    const testimonials = JSON.parse(el.dataset.testimonials || '[]');
    createRoot(el).render(<TestimonialsCarousel testimonials={testimonials} />);
});

// To mount another component: drop a <div data-react-root="my-widget"></div>
// in a blade view, add a resources/js/react/MyWidget.jsx, and register it
// the same way above.
