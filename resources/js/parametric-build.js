import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import * as THREE from 'three';

const BUILD_STAGES = [
    { id: 'foundations', trade: 'Kết cấu nền' },
    { id: 'structure', trade: 'Kết cấu chính' },
    { id: 'floors', trade: 'Sàn & giao thông' },
    { id: 'envelope', trade: 'Bao che' },
    { id: 'roof', trade: 'Mái' },
    { id: 'finishes', trade: 'Hoàn thiện' },
];

const clamp = (value, minimum = 0, maximum = 1) => Math.min(Math.max(value, minimum), maximum);
const easeOutCubic = (value) => 1 - ((1 - value) ** 3);

function createParametricHouse() {
    const root = new THREE.Group();
    const parts = [];
    const unitBox = new THREE.BoxGeometry(1, 1, 1);
    const unitCylinder = new THREE.CylinderGeometry(1, 1, 1, 12);
    const materialPalette = {
        concrete: { color: 0xb8b1a6, roughness: 0.88, metalness: 0.02 },
        steel: { color: 0x293b4b, roughness: 0.42, metalness: 0.55 },
        timber: { color: 0xb67a4e, roughness: 0.68, metalness: 0.02 },
        wall: { color: 0xe9e6de, roughness: 0.9, metalness: 0 },
        accent: { color: 0xb94755, roughness: 0.62, metalness: 0.04 },
        glass: { color: 0x8dc6dd, roughness: 0.12, metalness: 0.18, transparent: true, opacity: 0.5 },
        landscape: { color: 0x5d765d, roughness: 0.96, metalness: 0 },
    };
    const stageMaterials = new Map();

    const getMaterial = (materialName, stageIndex) => {
        const key = `${materialName}:${stageIndex}`;

        if (!stageMaterials.has(key)) {
            stageMaterials.set(key, new THREE.MeshStandardMaterial({
                ...materialPalette[materialName],
                emissive: 0xc95360,
                emissiveIntensity: 0,
            }));
        }

        return stageMaterials.get(key);
    };

    const addPart = ({
        size,
        position,
        stage,
        order,
        from,
        trade,
        material = 'concrete',
        geometry = unitBox,
        rotation = [0, 0, 0],
    }) => {
        const mesh = new THREE.Mesh(geometry, getMaterial(material, stage));
        const finalPosition = new THREE.Vector3(...position);
        const entryOffset = new THREE.Vector3(...from);

        mesh.position.copy(finalPosition).add(entryOffset);
        mesh.scale.set(...size);
        mesh.rotation.set(...rotation);
        mesh.visible = false;
        mesh.userData.build = {
            stage: BUILD_STAGES[stage].id,
            stageIndex: stage,
            order: clamp(order),
            from: [...from],
            trade,
            finalPosition,
            entryOffset,
            finalScale: new THREE.Vector3(...size),
        };

        root.add(mesh);
        parts.push(mesh);

        return mesh;
    };

    const xGrid = [-6.6, -3.3, 0, 3.3, 6.6];
    const zGrid = [-4.6, -1.53, 1.53, 4.6];
    const normalizedOrder = (index, total) => total <= 1 ? 0 : index / (total - 1);

    xGrid.forEach((x, xIndex) => {
        zGrid.forEach((z, zIndex) => {
            addPart({
                size: [1.15, 0.42, 1.15],
                position: [x, -0.3, z],
                stage: 0,
                order: normalizedOrder((xIndex * zGrid.length) + zIndex, xGrid.length * zGrid.length),
                from: [0, -3.5, 0],
                trade: 'foundation',
            });
        });
    });

    zGrid.forEach((z, zIndex) => {
        xGrid.slice(0, -1).forEach((x, xIndex) => {
            addPart({
                size: [3.3, 0.32, 0.32],
                position: [(x + xGrid[xIndex + 1]) / 2, 0.05, z],
                stage: 0,
                order: normalizedOrder((zIndex * 4) + xIndex, 16),
                from: [x < 0 ? -3 : 3, -1.2, 0],
                trade: 'foundation-beam',
            });
        });
    });

    xGrid.forEach((x, xIndex) => {
        zGrid.slice(0, -1).forEach((z, zIndex) => {
            addPart({
                size: [0.32, 0.32, 3.07],
                position: [x, 0.05, (z + zGrid[zIndex + 1]) / 2],
                stage: 0,
                order: normalizedOrder((xIndex * 3) + zIndex, 15),
                from: [0, -1.2, z < 0 ? -3 : 3],
                trade: 'foundation-beam',
            });
        });
    });

    for (let row = 0; row < 3; row += 1) {
        for (let column = 0; column < 4; column += 1) {
            addPart({
                size: [3.18, 0.18, 2.95],
                position: [-4.95 + (column * 3.3), 0.27, -3.06 + (row * 3.06)],
                stage: 0,
                order: normalizedOrder((row * 4) + column, 12),
                from: [0, -2.2, 0],
                trade: 'ground-slab',
            });
        }
    }

    [1.9, 5.1].forEach((columnY, levelIndex) => {
        xGrid.forEach((x, xIndex) => {
            zGrid.forEach((z, zIndex) => {
                addPart({
                    size: [0.3, 3.2, 0.3],
                    position: [x, columnY, z],
                    stage: 1,
                    order: normalizedOrder((levelIndex * 20) + (xIndex * 4) + zIndex, 40),
                    from: [0, 5 + (levelIndex * 1.5), 0],
                    trade: 'columns',
                    material: 'steel',
                });
            });
        });
    });

    [3.52, 6.72].forEach((beamY, levelIndex) => {
        zGrid.forEach((z, zIndex) => {
            xGrid.slice(0, -1).forEach((x, xIndex) => {
                addPart({
                    size: [3.3, 0.28, 0.28],
                    position: [(x + xGrid[xIndex + 1]) / 2, beamY, z],
                    stage: 1,
                    order: normalizedOrder((levelIndex * 31) + (zIndex * 4) + xIndex, 62),
                    from: [x < 0 ? -4 : 4, 2.8, 0],
                    trade: 'primary-beams',
                    material: 'steel',
                });
            });
        });

        xGrid.forEach((x, xIndex) => {
            zGrid.slice(0, -1).forEach((z, zIndex) => {
                addPart({
                    size: [0.28, 0.28, 3.07],
                    position: [x, beamY, (z + zGrid[zIndex + 1]) / 2],
                    stage: 1,
                    order: normalizedOrder((levelIndex * 31) + 16 + (xIndex * 3) + zIndex, 62),
                    from: [0, 2.8, z < 0 ? -4 : 4],
                    trade: 'primary-beams',
                    material: 'steel',
                });
            });
        });
    });

    for (let joist = 0; joist < 17; joist += 1) {
        addPart({
            size: [0.15, 0.2, 9.2],
            position: [-6.4 + (joist * 0.8), 3.7, 0],
            stage: 2,
            order: normalizedOrder(joist, 17),
            from: [0, 4.5, 0],
            trade: 'floor-joists',
            material: 'timber',
        });
    }

    for (let row = 0; row < 3; row += 1) {
        for (let column = 0; column < 4; column += 1) {
            addPart({
                size: [3.18, 0.16, 2.95],
                position: [-4.95 + (column * 3.3), 3.88, -3.06 + (row * 3.06)],
                stage: 2,
                order: normalizedOrder((row * 4) + column, 12),
                from: [0, 4, 0],
                trade: 'upper-deck',
                material: 'timber',
            });
        }
    }

    for (let tread = 0; tread < 13; tread += 1) {
        addPart({
            size: [1.8, 0.14, 0.62],
            position: [3.8, 0.52 + (tread * 0.245), 2.7 - (tread * 0.36)],
            stage: 2,
            order: normalizedOrder(tread, 13),
            from: [2.5, 1.5, 0],
            trade: 'stairs',
            material: 'timber',
        });
    }

    const addFacadePanel = (position, size, order, from, material = 'wall') => addPart({
        size,
        position,
        stage: 3,
        order,
        from,
        trade: material === 'glass' ? 'glazing' : 'facade',
        material,
    });

    [-4.6, 4.6].forEach((z, sideIndex) => {
        [-4.95, -1.65, 1.65, 4.95].forEach((x, index) => {
            const isGlass = (sideIndex === 0 && index > 0) || (sideIndex === 1 && index < 2);
            [2.05, 5.25].forEach((y, levelIndex) => {
                addFacadePanel(
                    [x, y, z],
                    [3.08, 2.75, isGlass ? 0.09 : 0.18],
                    normalizedOrder((sideIndex * 8) + (levelIndex * 4) + index, 16),
                    [0, 0.6, sideIndex === 0 ? -5 : 5],
                    isGlass ? 'glass' : 'wall',
                );
            });
        });
    });

    [-6.6, 6.6].forEach((x, sideIndex) => {
        [-3.06, 0, 3.06].forEach((z, index) => {
            [2.05, 5.25].forEach((y, levelIndex) => {
                addFacadePanel(
                    [x, y, z],
                    [0.18, 2.75, 2.88],
                    normalizedOrder((sideIndex * 6) + (levelIndex * 3) + index, 12),
                    [sideIndex === 0 ? -5 : 5, 0.6, 0],
                );
            });
        });
    });

    for (let slat = 0; slat < 18; slat += 1) {
        addPart({
            size: [0.1, 2.9, 0.22],
            position: [-6.15 + (slat * 0.72), 5.25, -4.84],
            stage: 3,
            order: normalizedOrder(slat, 18),
            from: [0, 1.8, -4],
            trade: 'sun-screen',
            material: 'timber',
        });
    }

    for (let roofBeam = 0; roofBeam < 15; roofBeam += 1) {
        addPart({
            size: [0.16, 0.24, 10.1],
            position: [-6.75 + (roofBeam * 0.965), 6.98, 0],
            stage: 4,
            order: normalizedOrder(roofBeam, 15),
            from: [0, 7, 0],
            trade: 'roof-frame',
            material: 'steel',
        });
    }

    for (let row = 0; row < 2; row += 1) {
        for (let column = 0; column < 6; column += 1) {
            addPart({
                size: [2.2, 0.16, 4.9],
                position: [-5.5 + (column * 2.2), 7.18, -2.5 + (row * 5)],
                stage: 4,
                order: normalizedOrder((row * 6) + column, 12),
                from: [0, 6, 0],
                trade: 'roof-panels',
                material: column > 3 && row === 0 ? 'accent' : 'wall',
            });
        }
    }

    for (let deckBoard = 0; deckBoard < 18; deckBoard += 1) {
        addPart({
            size: [0.56, 0.1, 3.4],
            position: [-4.8 + (deckBoard * 0.58), 0.44, -6.1],
            stage: 5,
            order: normalizedOrder(deckBoard, 18),
            from: [0, 2.5, -3],
            trade: 'external-deck',
            material: 'timber',
        });
    }

    for (let planter = 0; planter < 10; planter += 1) {
        const side = planter % 2 === 0 ? -1 : 1;
        addPart({
            size: [0.65, 0.7 + ((planter % 3) * 0.18), 0.65],
            position: [side * (8.2 + ((planter % 3) * 0.5)), 0.35, -4.2 + (planter * 0.9)],
            stage: 5,
            order: normalizedOrder(planter, 10),
            from: [side * 3, 0.5, 0],
            trade: 'landscape',
            material: 'landscape',
            geometry: unitCylinder,
        });
    }

    [-5.6, 5.6].forEach((x, sideIndex) => {
        for (let rail = 0; rail < 7; rail += 1) {
            addPart({
                size: [0.07, 1.05, 0.07],
                position: [x, 4.48, -3.9 + (rail * 1.3)],
                stage: 5,
                order: normalizedOrder((sideIndex * 7) + rail, 14),
                from: [sideIndex === 0 ? -2 : 2, 2, 0],
                trade: 'balustrade',
                material: 'steel',
            });
        }
    });

    return { root, parts, stageMaterials };
}

function updateBuildProgress(parts, stageMaterials, progress) {
    const stageSpan = 1 / BUILD_STAGES.length;
    const currentStageIndex = Math.min(Math.floor(progress / stageSpan), BUILD_STAGES.length - 1);

    parts.forEach((part) => {
        const build = part.userData.build;
        const partStart = (build.stageIndex + (build.order * 0.7)) * stageSpan;
        const partEnd = partStart + (stageSpan * 0.3);
        const reveal = easeOutCubic(clamp((progress - partStart) / (partEnd - partStart)));

        part.visible = reveal > 0.002;
        part.position.copy(build.finalPosition).addScaledVector(build.entryOffset, 1 - reveal);
        part.scale.copy(build.finalScale).multiplyScalar(0.72 + (reveal * 0.28));
    });

    stageMaterials.forEach((material, key) => {
        const stageIndex = Number(key.split(':')[1]);
        material.emissiveIntensity = stageIndex === currentStageIndex ? 0.11 : 0;
    });

    return currentStageIndex;
}

function createScene(canvas) {
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x07111b);
    scene.fog = new THREE.FogExp2(0x07111b, 0.018);

    const renderer = new THREE.WebGLRenderer({
        canvas,
        antialias: true,
        powerPreference: 'high-performance',
    });
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.05;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, window.innerWidth < 768 ? 1.25 : 1.65));

    const camera = new THREE.PerspectiveCamera(34, 1, 0.1, 120);
    camera.position.set(18, 13, 20);
    camera.lookAt(0, 3.1, 0);

    const hemisphere = new THREE.HemisphereLight(0xcce8f7, 0x1b2632, 2.25);
    const keyLight = new THREE.DirectionalLight(0xfff2db, 3.2);
    const fillLight = new THREE.DirectionalLight(0xc95360, 1.15);
    keyLight.position.set(12, 20, 10);
    fillLight.position.set(-14, 9, -8);
    scene.add(hemisphere, keyLight, fillLight);

    const ground = new THREE.Mesh(
        new THREE.PlaneGeometry(64, 64),
        new THREE.MeshStandardMaterial({ color: 0x0d1a25, roughness: 1, metalness: 0 }),
    );
    ground.rotation.x = -Math.PI / 2;
    ground.position.y = -0.53;
    scene.add(ground);

    const grid = new THREE.GridHelper(64, 64, 0x385063, 0x1c2b37);
    grid.position.y = -0.51;
    grid.material.opacity = 0.42;
    grid.material.transparent = true;
    scene.add(grid);

    return { scene, renderer, camera };
}

function initializeLenis() {
    const lenis = new Lenis({
        autoRaf: true,
        duration: 1.05,
        smoothWheel: true,
        syncTouch: false,
        touchMultiplier: 1.15,
    });

    lenis.on('scroll', ScrollTrigger.update);

    return lenis;
}

export function initParametricBuild(section) {
    if (!section || section.dataset.buildInitialized === 'true') {
        return;
    }

    section.dataset.buildInitialized = 'true';
    gsap.registerPlugin(ScrollTrigger);

    const viewport = section.querySelector('[data-build-viewport]');
    const canvas = section.querySelector('[data-build-canvas]');
    const componentCount = section.querySelector('[data-build-component-count]');
    const progressElement = section.querySelector('[data-build-progress]');
    const progressValue = section.querySelector('[data-build-progress-value]');
    const stageNumber = section.querySelector('[data-build-stage-number]');
    const stageTitle = section.querySelector('[data-build-stage-title]');
    const stageDescription = section.querySelector('[data-build-stage-description]');
    const stageTrade = section.querySelector('[data-build-stage-trade]');
    const stageButtons = [...section.querySelectorAll('[data-build-stage-button]')];
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!viewport || !canvas || !progressElement || stageButtons.length !== BUILD_STAGES.length) {
        section.classList.add('is-unavailable');
        return;
    }

    let scene;
    let renderer;
    let camera;
    let model;

    try {
        ({ scene, renderer, camera } = createScene(canvas));
        model = createParametricHouse();
        scene.add(model.root);
    } catch (error) {
        section.classList.add('is-unavailable');
        console.warn('ACONS parametric build could not start.', error);
        return;
    }

    if (componentCount) {
        componentCount.textContent = new Intl.NumberFormat('vi-VN').format(model.parts.length);
    }

    const pointer = new THREE.Vector2();
    const easedPointer = new THREE.Vector2();
    let currentProgress = reducedMotion ? 1 : 0;
    let currentStageIndex = -1;
    let isRendering = false;

    const resizeScene = () => {
        const { width, height } = viewport.getBoundingClientRect();

        renderer.setSize(width, height, false);
        camera.aspect = width / Math.max(height, 1);
        camera.fov = width < 768 ? 43 : 34;
        camera.position.set(width < 768 ? 19 : 18, width < 768 ? 15 : 13, width < 768 ? 25 : 20);
        camera.updateProjectionMatrix();
        camera.lookAt(0, 3.1, 0);
    };

    const updateInterface = (progress, nextStageIndex) => {
        const percentage = Math.round(progress * 100);
        const activeButton = stageButtons[nextStageIndex];

        progressElement.style.setProperty('--build-progress', progress);
        progressElement.setAttribute('aria-valuenow', String(percentage));

        if (progressValue) {
            progressValue.textContent = String(percentage).padStart(2, '0');
        }

        if (nextStageIndex === currentStageIndex || !activeButton) {
            return;
        }

        currentStageIndex = nextStageIndex;
        stageButtons.forEach((button, index) => {
            const isActive = index === currentStageIndex;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-current', isActive ? 'step' : 'false');
        });

        if (stageNumber) {
            stageNumber.textContent = String(currentStageIndex + 1).padStart(2, '0');
        }
        if (stageTitle) {
            stageTitle.textContent = activeButton.dataset.stageTitle || '';
        }
        if (stageDescription) {
            stageDescription.textContent = activeButton.dataset.stageDescription || '';
        }
        if (stageTrade) {
            stageTrade.textContent = activeButton.dataset.stageTrade || BUILD_STAGES[currentStageIndex].trade;
        }
    };

    const setProgress = (progress) => {
        currentProgress = clamp(progress);
        const nextStageIndex = updateBuildProgress(model.parts, model.stageMaterials, currentProgress);

        model.root.rotation.y = -0.12 + (currentProgress * 0.28);
        updateInterface(currentProgress, nextStageIndex);

        if (!isRendering) {
            renderer.render(scene, camera);
        }
    };

    const renderFrame = () => {
        easedPointer.lerp(pointer, 0.045);
        model.root.rotation.x = easedPointer.y * 0.035;
        model.root.rotation.y = -0.12 + (currentProgress * 0.28) + (easedPointer.x * 0.1);
        renderer.render(scene, camera);
    };

    const startRendering = () => {
        if (isRendering || document.hidden) {
            return;
        }

        isRendering = true;
        renderer.setAnimationLoop(renderFrame);
    };

    const stopRendering = () => {
        isRendering = false;
        renderer.setAnimationLoop(null);
    };

    viewport.addEventListener('pointermove', (event) => {
        const bounds = viewport.getBoundingClientRect();
        pointer.set(
            ((event.clientX - bounds.left) / bounds.width) * 2 - 1,
            -(((event.clientY - bounds.top) / bounds.height) * 2 - 1),
        );
    });
    viewport.addEventListener('pointerleave', () => pointer.set(0, 0));

    const visibilityObserver = new IntersectionObserver((entries) => {
        if (entries.some((entry) => entry.isIntersecting)) {
            startRendering();
        } else {
            stopRendering();
        }
    }, { threshold: 0.02 });
    visibilityObserver.observe(section);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopRendering();
        } else if (section.getBoundingClientRect().bottom > 0 && section.getBoundingClientRect().top < window.innerHeight) {
            startRendering();
        }
    });

    const resizeObserver = new ResizeObserver(resizeScene);
    resizeObserver.observe(viewport);
    resizeScene();

    if (reducedMotion) {
        section.classList.add('is-static');
        setProgress(1);
        renderer.render(scene, camera);
        return;
    }

    const lenis = initializeLenis();
    const buildTimeline = ScrollTrigger.create({
        trigger: section,
        start: 'top top',
        end: () => `+=${window.innerHeight * 5.25}`,
        pin: viewport,
        pinSpacing: true,
        scrub: 0.45,
        anticipatePin: 1,
        invalidateOnRefresh: true,
        onUpdate: (self) => setProgress(self.progress),
    });

    stageButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const stageProgress = (index + 0.08) / BUILD_STAGES.length;
            const destination = buildTimeline.start + ((buildTimeline.end - buildTimeline.start) * stageProgress);
            lenis.scrollTo(destination, { duration: 1.1 });
        });
    });

    setProgress(0);
    window.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });
}
