export default function ApplicationLogo(props) {
    return (
        <img
            src="/logoYogyakarta.svg"
            alt="Logo Yogyakarta"
            {...props}
            className={`object-contain ${props.className || ''}`}
        />
    );
}
