import type { APIRoute } from "astro";

export const POST: APIRoute = async (contexto) => {
	const { cedula, clave } = await contexto.request.json();

	if (Number(cedula) === 28072391 && clave === "Fran.0610") {
		return new Response(JSON.stringify({ mensaje: "Ingreso exitoso" }), {
			status: 200,
		});
	}

	return new Response(JSON.stringify({ mensaje: "Credenciales incorrectas" }), {
		status: 401,
	});
};
