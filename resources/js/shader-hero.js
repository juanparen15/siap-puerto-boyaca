/**
 * WebGL2 Shader Hero — ported from React to vanilla JS
 * Shader: animated fire/nebula clouds by Matthias Hurrle (@atzedent)
 */

const VERTEX_SRC = `#version 300 es
precision highp float;
in vec4 position;
void main(){gl_Position=position;}`;

const FRAGMENT_SRC = `#version 300 es
precision highp float;
out vec4 O;
uniform vec2 resolution;
uniform float time;
#define FC gl_FragCoord.xy
#define T time
#define R resolution
#define MN min(R.x,R.y)

float rnd(vec2 p) {
  p=fract(p*vec2(12.9898,78.233));
  p+=dot(p,p+34.56);
  return fract(p.x*p.y);
}
float noise(in vec2 p) {
  vec2 i=floor(p), f=fract(p), u=f*f*(3.-2.*f);
  float a=rnd(i),b=rnd(i+vec2(1,0)),c=rnd(i+vec2(0,1)),d=rnd(i+1.);
  return mix(mix(a,b,u.x),mix(c,d,u.x),u.y);
}
float fbm(vec2 p) {
  float t=.0, a=1.;
  mat2 m=mat2(1.,-.5,.2,1.2);
  for(int i=0;i<5;i++){t+=a*noise(p);p*=2.*m;a*=.5;}
  return t;
}
float clouds(vec2 p) {
  float d=1.,t=.0;
  for(float i=.0;i<3.;i++){
    float a=d*fbm(i*10.+p.x*.2+.2*(1.+i)*p.y+d+i*i+p);
    t=mix(t,d,a);d=a;p*=2./(i+1.);
  }
  return t;
}
void main(void) {
  vec2 uv=(FC-.5*R)/MN, st=uv*vec2(2,1);
  vec3 col=vec3(0);
  float bg=clouds(vec2(st.x+T*.5,-st.y));
  uv*=1.-.3*(sin(T*.2)*.5+.5);
  for(float i=1.;i<12.;i++){
    uv+=.1*cos(i*vec2(.1+.01*i,.8)+i*i+T*.5+.1*uv.x);
    vec2 p=uv;
    float d=length(p);
    col+=.00125/d*(cos(sin(i)*vec3(1,2,3))+1.);
    float b=noise(i+p+bg*1.731);
    col+=.002*b/length(max(p,vec2(b*p.x*.02,p.y)));
    col=mix(col,vec3(bg*.25,bg*.137,bg*.05),d);
  }
  O=vec4(col,1);
}`;

class ShaderHero {
    constructor(canvas) {
        this.canvas  = canvas;
        this.gl      = canvas.getContext('webgl2');
        this.program = null;
        this.raf     = null;
        this.mouse   = [0, 0];
        this.dpr     = Math.max(1, 0.5 * window.devicePixelRatio);

        if (!this.gl) {
            console.warn('WebGL2 not supported — hero shader disabled');
            return;
        }

        this._build();
        this._resize();
        this._listen();
        this._loop(0);
    }

    _compile(type, src) {
        const gl     = this.gl;
        const shader = gl.createShader(type);
        gl.shaderSource(shader, src);
        gl.compileShader(shader);
        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            console.error('Shader compile error:', gl.getShaderInfoLog(shader));
        }
        return shader;
    }

    _build() {
        const gl   = this.gl;
        const vs   = this._compile(gl.VERTEX_SHADER,   VERTEX_SRC);
        const fs   = this._compile(gl.FRAGMENT_SHADER, FRAGMENT_SRC);
        const prog = gl.createProgram();

        gl.attachShader(prog, vs);
        gl.attachShader(prog, fs);
        gl.linkProgram(prog);

        if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) {
            console.error('Program link error:', gl.getProgramInfoLog(prog));
        }

        // Buffer
        const buf = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buf);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1,1,-1,-1,1,1,1,-1]), gl.STATIC_DRAW);

        const pos = gl.getAttribLocation(prog, 'position');
        gl.enableVertexAttribArray(pos);
        gl.vertexAttribPointer(pos, 2, gl.FLOAT, false, 0, 0);

        prog.uRes  = gl.getUniformLocation(prog, 'resolution');
        prog.uTime = gl.getUniformLocation(prog, 'time');
        prog.uMove = gl.getUniformLocation(prog, 'move');
        prog.uTouch = gl.getUniformLocation(prog, 'touch');

        this.program = prog;
    }

    _resize() {
        const dpr = this.dpr;
        this.canvas.width  = window.innerWidth  * dpr;
        this.canvas.height = window.innerHeight * dpr;
        this.canvas.style.width  = window.innerWidth  + 'px';
        this.canvas.style.height = window.innerHeight + 'px';
        if (this.gl) this.gl.viewport(0, 0, this.canvas.width, this.canvas.height);
    }

    _listen() {
        window.addEventListener('resize', () => this._resize());
        this.canvas.addEventListener('pointermove', (e) => {
            this.mouse = [e.clientX * this.dpr, this.canvas.height - e.clientY * this.dpr];
        });
    }

    _loop(now) {
        const gl   = this.gl;
        const prog = this.program;
        if (!gl || !prog) return;

        gl.clearColor(0, 0, 0, 1);
        gl.clear(gl.COLOR_BUFFER_BIT);
        gl.useProgram(prog);
        gl.uniform2f(prog.uRes,   this.canvas.width, this.canvas.height);
        gl.uniform1f(prog.uTime,  now * 1e-3);
        gl.uniform2f(prog.uMove,  0, 0);
        gl.uniform2f(prog.uTouch, this.mouse[0], this.mouse[1]);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);

        this.raf = requestAnimationFrame((t) => this._loop(t));
    }

    destroy() {
        if (this.raf) cancelAnimationFrame(this.raf);
        if (this.gl && this.program) this.gl.deleteProgram(this.program);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('shader-hero-canvas');
    if (canvas) window._shaderHero = new ShaderHero(canvas);
});
