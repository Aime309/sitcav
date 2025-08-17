import type { APIRoute } from "astro";

export const POST: APIRoute = async (contexto) => {
	const datos = await contexto.request.json();
	const { cedula, clave, pregunta_secreta, respuesta_secreta } = datos;

	if (Number(cedula) === 28072391) {
		return new Response(
			JSON.stringify({ mensaje: "Cuenta creada exitosamente" }),
			{
				status: 200,
			},
		);
	}

	return new Response(JSON.stringify({ mensaje: "Error al crear la cuenta" }), {
		status: 400,
	});
};
