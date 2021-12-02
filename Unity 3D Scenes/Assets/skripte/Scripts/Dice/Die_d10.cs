/**
 * Copyright (c) 2010-2015, WyrmTale Games and Game Components
 * All rights reserved.
 * http://www.wyrmtale.com
 *
 * THIS SOFTWARE IS PROVIDED BY WYRMTALE GAMES AND GAME COMPONENTS 'AS IS' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL WYRMTALE GAMES AND GAME COMPONENTS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR 
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */ 
using UnityEngine;
using System.Collections;

// Die subclass to expose the D10 side hitVectors
public class Die_d10 : Die {
		
    override protected Vector3 HitVector(int side)
    {
        switch (side)
        {
            case 1: return new Vector3(0.4F, -0.7F, 0.7F);
            case 2: return new Vector3(0.4F, 0.7F, -0.7F);
            case 3: return new Vector3(-0.7F, 0.2F, 0.7F);
            case 4: return new Vector3(0F, -0.7F, -0.7F);
            case 5: return new Vector3(0F, 0.7F, 0.7F);
            case 6: return new Vector3(0.7F, -0.2F, -0.7F);
            case 7: return new Vector3(-0.4F, -0.7F, 0.7F);
            case 8: return new Vector3(-0.4F, 0.7F, -0.7F);
            case 9: return new Vector3(0.7F, 0.2F, 0.7F);
            case 10: return new Vector3(-0.7F, -0.2F, -0.7F);
        }
        return Vector3.zero;
    }
	
}
