<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("livewire:init", () => {
        Livewire.on("swal", (event) => {
            const data = event[0] || event;
            const isDark = document.documentElement.classList.contains("dark");
            
            Swal.fire({
                title: data.title,
                html: data.html,
                icon: data.icon,
                confirmButtonColor: "#19499C",
                background: isDark ? "#111827" : "#ffffff",
                color: isDark ? "#ffffff" : "#374151",
            });
        });
    });

    /* =========================================================
       MENÚ DE SELECCIÓN DE RECORRIDO GUIADO (SOBRIO Y PROFESIONAL)
       ========================================================= */
    window.abrirMenuTourOpciones = function() {
        const isDark = document.documentElement.classList.contains("dark");
        Swal.fire({
            title: '<div style="font-size: 15px; font-weight: 700; color: #0f172a; text-align: left; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">Guía Operativa CIAC TAMEP</div>',
            html: `
                <div style="text-align: left; padding-top: 6px;">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 12px; line-height: 1.45;">
                        Seleccione el procedimiento que desea recorrer directamente en la pantalla de trabajo:
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <button onclick="window.iniciarRutaTour('manual')" style="padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer; text-align: left; display: flex; align-items: flex-start; gap: 10px; transition: all 0.15s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1';">
                            <span style="width: 28px; height: 28px; border-radius: 6px; background: rgba(25,73,156,0.1); color: #19499C; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; flex-shrink: 0; margin-top: 1px;">1</span>
                            <div>
                                <div style="font-size: 12.5px; font-weight: 700; color: #0f172a;">1. Registro Individual de Certificado</div>
                                <div style="font-size: 11px; color: #64748b;">Recorre campo por campo en el formulario oficial (nombres, C.I., curso y Libro Matriz).</div>
                            </div>
                        </button>

                        <button onclick="window.iniciarRutaTour('masivo')" style="padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer; text-align: left; display: flex; align-items: flex-start; gap: 10px; transition: all 0.15s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1';">
                            <span style="width: 28px; height: 28px; border-radius: 6px; background: rgba(5,150,105,0.1); color: #059669; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; flex-shrink: 0; margin-top: 1px;">2</span>
                            <div>
                                <div style="font-size: 12.5px; font-weight: 700; color: #0f172a;">2. Importación Masiva desde Excel</div>
                                <div style="font-size: 11px; color: #64748b;">Muestra la ubicación del botón de carga masiva y las pestañas por especialidad.</div>
                            </div>
                        </button>

                        <button onclick="window.iniciarRutaTour('exportacion')" style="padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer; text-align: left; display: flex; align-items: flex-start; gap: 10px; transition: all 0.15s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1';">
                            <span style="width: 28px; height: 28px; border-radius: 6px; background: rgba(217,119,6,0.1); color: #d97706; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; flex-shrink: 0; margin-top: 1px;">3</span>
                            <div>
                                <div style="font-size: 12.5px; font-weight: 700; color: #0f172a;">3. Reportes y Exportación con QRs</div>
                                <div style="font-size: 11px; color: #64748b;">Muestra los filtros de carrera y el botón de exportación de nómina con imágenes QR incrustadas.</div>
                            </div>
                        </button>

                        <a href="/admin/guia-interactiva" style="margin-top: 4px; padding: 8px 12px; border-radius: 6px; background: #f1f5f9; color: #1e293b; text-align: center; font-size: 11.5px; font-weight: 600; text-decoration: none; border: 1px solid #e2e8f0; display: block;" onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f1f5f9';">
                            Ver Documentación Completa en Guía Operativa &rarr;
                        </a>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            background: isDark ? "#111827" : "#ffffff",
            width: 440
        });
    };

    /* Redirección precisa al módulo real correspondiente */
    window.iniciarRutaTour = function(tipo) {
        if (typeof Swal !== "undefined") Swal.close();
        const path = window.location.pathname;

        if (tipo === "manual") {
            if (path.includes("/admin/certificados/create")) {
                window.iniciarTourGuiadoInteractivo("manual");
            } else {
                window.location.href = "/admin/certificados/create?tour=manual";
            }
        } else if (tipo === "masivo") {
            if (path.includes("/admin/certificados") && !path.includes("/create")) {
                window.iniciarTourGuiadoInteractivo("masivo");
            } else {
                window.location.href = "/admin/certificados?tour=masivo";
            }
        } else if (tipo === "exportacion") {
            if (path.includes("/admin/reportes")) {
                window.iniciarTourGuiadoInteractivo("exportacion");
            } else {
                window.location.href = "/admin/reportes?tour=exportacion";
            }
        }
    };

    /* =========================================================
       BUSCADOR INTELIGENTE DE ELEMENTOS REALES (SIN UBICACIONES FALSAS)
       ========================================================= */
    function encontrarElementoReal(selector) {
        if (!selector) return null;
        const selectores = selector.split(",").map(s => s.trim());

        for (const sel of selectores) {
            try {
                const el = document.querySelector(sel);
                if (el) {
                    // Si el elemento es un input o control interno, expandir al contenedor visible del campo
                    const wrapper = el.closest(".fi-fo-field-wrp") || 
                                    el.closest(".fi-ta-header-action") || 
                                    el.closest(".fi-ac-action-group") || 
                                    el;

                    const rect = wrapper.getBoundingClientRect();
                    // Solo retornar si el elemento tiene dimensiones visibles reales en la pantalla
                    if (rect.width > 20 && rect.height > 20) {
                        return wrapper;
                    }
                }
            } catch (e) {
                // Continuar con el siguiente selector
            }
        }
        return null;
    }

    /* =========================================================
       MOTOR DEL TOUR SPOTLIGHT (PRECISO Y ESTABLE)
       ========================================================= */
    window.iniciarTourGuiadoInteractivo = function(modo) {
        const path = window.location.pathname;
        const urlParams = new URLSearchParams(window.location.search);
        const tipo = modo || urlParams.get("tour") || "manual";
        let steps = [];

        // 1. TOUR EN PANTALLA REAL: FORMULARIO DE REGISTRO MANUAL
        if (tipo === "manual" && path.includes("/admin/certificados/create")) {
            steps = [
                {
                    selector: "[wire\\:key*='apellido_paterno_cer'], [id*='apellido_paterno_cer'], [name*='apellido_paterno_cer']",
                    title: "1. Apellidos del Estudiante",
                    desc: "Ingrese Apellido Paterno y Materno. El sistema normaliza todo el texto a MAYÚSCULAS automáticamente al escribir."
                },
                {
                    selector: "[wire\\:key*='nombre_per_cer'], [id*='nombre_per_cer'], [name*='nombre_per_cer']",
                    title: "2. Nombres del Estudiante",
                    desc: "Ingrese los nombres de pila completos tal como figuran en el documento de identidad legal."
                },
                {
                    selector: "[wire\\:key*='carnet_per_cer'], [id*='carnet_per_cer'], [name*='carnet_per_cer']",
                    title: "3. Cédula de Identidad (C.I.)",
                    desc: "Número de cédula sin puntos ni espacios. Es la clave obligatoria que garantiza la no duplicidad del registro."
                },
                {
                    selector: "[wire\\:key*='curso_cer'], [id*='curso_cer'], select[id*='curso_cer']",
                    title: "4. Especialidad Académica (Curso CIAC)",
                    desc: "Seleccione Despachador de Vuelo, Tripulante de Cabina o Agente de Tráfico. El sistema autocompletará el código (DV/2026, TCP/2026 o AGT/2026), fechas lectivas y autoridad firmante."
                },
                {
                    selector: "[wire\\:key*='estado_academico_cer'], [id*='estado_academico_cer'], [wire\\:key*='promedio_final_cer']",
                    title: "5. Condición y Calificación Final",
                    desc: "Verifique la condición (APROBADO / REPROBADO) y la nota obtenida por el alumno en la cohorte."
                },
                {
                    selector: "[wire\\:key*='registro_diploma_cer'], [id*='registro_diploma_cer'], [wire\\:key*='registro_estudios_cer']",
                    title: "6. Registro Oficial en Libro Matriz",
                    desc: "Asigne o revise el folio institucional reglamentario (ej: CIAC TAMEP DV 2026 CI 9895606 N° 001/26)."
                },
                {
                    selector: "form.fi-form button[type='submit'], .fi-form-actions button",
                    title: "7. Emisión y Generación de Código QR",
                    desc: "Al hacer clic en 'Crear', se sella el registro en la base de datos y se genera de forma inmediata el código QR físico para verificación digital."
                }
            ];
        }
        // 2. TOUR EN PANTALLA REAL: LISTADO DE CERTIFICADOS (CARGA MASIVA)
        else if (tipo === "masivo" && path.includes("/admin/certificados") && !path.includes("/create")) {
            steps = [
                {
                    selector: ".fi-page-header-actions .fi-ac-action-group, .fi-page-header-actions button",
                    title: "1. Botón de Registro Múltiple (Excel)",
                    desc: "En este menú superior de acciones se encuentra la opción 'Registro Múltiple (Carga Excel)' para abrir la ventana de importación masiva."
                },
                {
                    selector: ".fi-tabs",
                    title: "2. Pestañas de Filtro por Especialidad",
                    desc: "Permite revisar y clasificar rápidamente los alumnos por programa: Gestión 2026, Agente de Tráfico, Despachador de Vuelo o Tripulante de Cabina."
                },
                {
                    selector: ".fi-ta-table, .fi-ta-content",
                    title: "3. Tabla Consolidada de Certificados",
                    desc: "Visualice los alumnos registrados con sus códigos QR generados, copiado de enlace seguro y folios de Libro Matriz."
                }
            ];
        }
        // 3. TOUR EN PANTALLA REAL: REPORTES Y EXPORTACIÓN CON QRS
        else if (tipo === "exportacion" && path.includes("/admin/reportes")) {
            steps = [
                {
                    selector: ".fi-ta-filters, form.fi-ta-filters-form, [wire\\:key*='curso_cer'], .fi-fo-field-wrp",
                    title: "1. Filtros de Selección por Especialidad",
                    desc: "Filtre la nómina por Programa Académico (DV, TCP, AGT), Gestión o Condición (Aprobados) para clasificar la lista."
                },
                {
                    selector: "button[wire\\:click*='exportarExcelFiltrado'], .fi-page-header-actions button, .fi-ta-header-actions button",
                    title: "2. Descarga de Excel con QRs Incrustados",
                    desc: "Haga clic en 'Exportar Filtrados a Excel' para generar el archivo con imágenes físicas PNG de los códigos QR (500x500 px) incrustadas en la Columna L."
                },
                {
                    selector: ".fi-ta-table, .tabla-excel",
                    title: "3. Nómina Lista para Impresión",
                    desc: "Tabla consolidada preparada para control interno, actas institucionales y firmas de encuadernación."
                }
            ];
        }
        // 4. SI EL USUARIO ESTÁ EN OTRA VISTA (EJ: GUÍA OPERATIVA)
        else {
            // Ofrecer redirección a la pantalla real adecuada
            window.abrirMenuTourOpciones();
            return;
        }

        // Limpiar elementos de tour previos
        const prevOverlay = document.getElementById("ciac-tour-overlay"); if (prevOverlay) prevOverlay.remove();
        const prevBox = document.getElementById("ciac-tour-spotlight-box"); if (prevBox) prevBox.remove();
        const prevCard = document.getElementById("ciac-tour-card"); if (prevCard) prevCard.remove();

        function createSpotlightBox() {
            const el = document.createElement("div");
            el.id = "ciac-tour-spotlight-box";
            el.style.position = "fixed";
            el.style.zIndex = "9999";
            el.style.borderRadius = "8px";
            el.style.border = "2.5px solid #19499C";
            el.style.boxShadow = "0 0 0 9999px rgba(15, 23, 42, 0.6)";
            el.style.pointerEvents = "none";
            el.style.transition = "all 0.25s ease";
            document.body.appendChild(el);
            return el;
        }

        function createCard() {
            const el = document.createElement("div");
            el.id = "ciac-tour-card";
            el.style.position = "fixed";
            el.style.zIndex = "10000";
            el.style.width = "340px";
            el.style.maxWidth = "calc(100vw - 32px)";
            el.style.backgroundColor = "#ffffff";
            el.style.borderRadius = "10px";
            el.style.boxShadow = "0 10px 25px rgba(0,0,0,0.18)";
            el.style.border = "1px solid #cbd5e1";
            el.style.transition = "all 0.25s ease";
            document.body.appendChild(el);
            return el;
        }

        window.closeCiacTour = function() {
            const s = document.getElementById("ciac-tour-spotlight-box"); if (s) s.remove();
            const c = document.getElementById("ciac-tour-card"); if (c) c.remove();
        };

        function showStep(index) {
            if (index < 0 || index >= steps.length) {
                window.closeCiacTour();
                return;
            }

            const step = steps[index];
            const targetEl = encontrarElementoReal(step.selector);
            const box = document.getElementById("ciac-tour-spotlight-box") || createSpotlightBox();
            const card = document.getElementById("ciac-tour-card") || createCard();

            if (targetEl) {
                targetEl.scrollIntoView({ behavior: "smooth", block: "center" });
                
                setTimeout(() => {
                    const rect = targetEl.getBoundingClientRect();
                    box.style.display = "block";
                    box.style.top = Math.max(0, rect.top - 4) + "px";
                    box.style.left = Math.max(0, rect.left - 4) + "px";
                    box.style.width = Math.min(window.innerWidth - 8, rect.width + 8) + "px";
                    box.style.height = (rect.height + 8) + "px";

                    let cardTop = rect.bottom + 12;
                    let cardLeft = Math.max(16, rect.left);
                    
                    if (cardTop + 210 > window.innerHeight) {
                        cardTop = Math.max(16, rect.top - 210);
                    }
                    if (cardLeft + 350 > window.innerWidth) {
                        cardLeft = Math.max(16, window.innerWidth - 360);
                    }

                    card.style.top = cardTop + "px";
                    card.style.left = cardLeft + "px";
                }, 100);
            } else {
                box.style.display = "none";
                card.style.top = "20%";
                card.style.left = "calc(50% - 170px)";
            }

            card.innerHTML = `
                <div style="padding: 16px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #1e293b;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 11px; font-weight: 700; background: #f1f5f9; color: #19499C; padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0;">Paso ${index + 1} de ${steps.length}</span>
                        <button onclick="window.closeCiacTour()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #94a3b8; line-height: 1;" title="Cerrar">&times;</button>
                    </div>
                    <h4 style="font-size: 13.5px; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">${step.title}</h4>
                    <p style="font-size: 12px; line-height: 1.45; color: #475569; margin: 0 0 12px 0;">${step.desc}</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                        <button onclick="window.tourStep(${index - 1})" ${index === 0 ? "disabled style=\"opacity:0.35; cursor:not-allowed; padding: 5px 10px; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 11.5px; font-weight: 600; color: #64748b;\"" : "style=\"cursor:pointer; padding: 5px 10px; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; font-size: 11.5px; font-weight: 600; color: #334155;\""}>Anterior</button>
                        <button onclick="window.tourStep(${index + 1})" style="cursor:pointer; padding: 5px 12px; border-radius: 6px; background: #19499C; color: #ffffff; border: 1px solid #19499C; font-size: 11.5px; font-weight: 600;">${index === steps.length - 1 ? "Finalizar" : "Siguiente"}</button>
                    </div>
                </div>
            `;
        }

        window.tourStep = function(i) {
            showStep(i);
        };

        showStep(0);
    };

    /* Ejecución automática si la URL contiene el parámetro ?tour=... */
    function verificarYEjecutarTourAutomatico() {
        const params = new URLSearchParams(window.location.search);
        const tour = params.get("tour");
        if (!tour) return;

        // Limpiar el parámetro de la URL sin recargar para no repetir en futuros refrescos
        window.history.replaceState({}, document.title, window.location.pathname);

        // Esperar a que Livewire y Filament terminen de renderizar los campos en el DOM
        let intentos = 0;
        const interval = setInterval(() => {
            intentos++;
            const listo = document.querySelector("[id*='apellido_paterno_cer'], [id*='curso_cer'], .fi-tabs, .fi-ta-filters, form.fi-form");
            if (listo || intentos >= 20) {
                clearInterval(interval);
                setTimeout(() => {
                    window.iniciarTourGuiadoInteractivo(tour);
                }, 300);
            }
        }, 120);
    }

    document.addEventListener("DOMContentLoaded", verificarYEjecutarTourAutomatico);
    document.addEventListener("livewire:navigated", verificarYEjecutarTourAutomatico);
</script>
