import neko from "../utils/manekineko";
import { useEffect } from 'react';

function NoPage() {
    useEffect(() => {
        neko();

        return () => {
            const container = document.getElementById('threejs-container');
            if (container) {
                const canvas = container.querySelector('canvas');
                if (canvas) {
                    container.removeChild(canvas);
                }
            }
        };
    }, []);

    return (
        <>
        <div className="threeJs">
            <div id="threejs-container"></div>
            <h1 className="notFound">Page non trouvée</h1>
        </div>
        </>
    );
}

export default NoPage;
